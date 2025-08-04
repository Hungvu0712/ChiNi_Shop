<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderStatusUpdated;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Mail\OtpEmail;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\CartItem;
use App\Jobs\SendOtpEmail;
use App\Models\VoucherLog;
use App\Models\OrderDetail;
use App\Models\VoucherMeta;
use App\Models\VoucherUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Notifications\OrderConfirmationNotification;
use App\Http\Controllers\Service\PaymentController;
use App\Models\OrderItem;
use App\Models\Variant;
use Cloudinary\Transformation\Argument\Range\Range;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (Auth::check()) {
                $user_id = Auth::id();
                // Get all orders for the authenticated user, including order details
                $orders = Order::query()
                    ->where('user_id', $user_id)
                    ->with([
                        'orderDetails',
                        'paymentMethod',
                    ])
                    ->latest('id')
                    ->paginate(5);
                return view("client.pages.order-management",compact('orders'));
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $data = $request->all();
            // dd($data);
            $isCartPurchase = isset($data['cart_item_ids']) && is_array($data['cart_item_ids']) && count($data['cart_item_ids']) > 0;
            $user = $this->getUser($data);

            $redirectResponse = null;

            DB::transaction(function () use ($data, $user, $isCartPurchase, &$redirectResponse) {
                $order = $this->createOrder($data, $user);
                $totalQuantity = 0;
                $totalPrice = 0.00;
                $errorStocks = [];

                if ($isCartPurchase && Auth::check()) {
                    list($quantity, $price, $errorStocks) = $this->addCartItemsToOrder($data, $user, $order);
                    // dd($this->addCartItemsToOrder($data, $user, $order));
                    $totalQuantity += $quantity;
                    $totalPrice += $price;
                }
                // dd($totalPrice);
                if (count($errorStocks)) {
                    $hasOutOfStockError = !empty($errorStocks['out_of_stock']);
                    $hasInsufficientStockError = !empty($errorStocks['insufficient_stock']);

                    if ($hasOutOfStockError || $hasInsufficientStockError) {
                        DB::rollBack();
                        if ($isCartPurchase) {
                            // Lấy lại giỏ hàng
                            $cart = Cart::query()
                                ->where('user_id', $user['id'])
                                ->with('cartitems.product', 'cartitems.productvariant.attributes')
                                ->first();

                            foreach ($cart->cartitems as $key => $cartItem) {
                                // Kiểm tra nếu sản phẩm hết hàng
                                if ($hasOutOfStockError) {
                                    foreach ($errorStocks['out_of_stock'] as $error) {
                                        // dd($error['cart_id']); 
                                        // Nếu sản phẩm hết hàng, xóa sản phẩm khỏi giỏ hàng
                                        if ($error['cart_id'] == $cartItem->id) {
                                            $cartItem->delete();
                                        }
                                    }
                                }
                                // Kiểm tra nếu số lượng yêu cầu lớn hơn số lượng có sẵn
                                if ($hasInsufficientStockError) {
                                    foreach ($errorStocks['insufficient_stock'] as $error) {
                                         
                                        // Nếu sản phẩm hết hàng, xóa sản phẩm khỏi giỏ hàng
                                        if ($error['cart_id'] == $cartItem->id) {
                                            $availableQuantity = $cartItem->productvariant ? $cartItem->productvariant->quantity : $cartItem->product->quantity;
                                            $cartItem->update(['quantity' => $availableQuantity]); // Cập nhật lại số lượng
                                        }
                                    }
                                    // Nếu không đủ số lượng, cập nhật lại số lượng trong giỏ hàng

                                }
                            }
                        }
                        $insufficientStockIds = array_column($errorStocks['insufficient_stock'] ?? [], 'cart_id');
                       
                        // Lọc lại chỉ lấy những cart_id có lỗi
                        $filteredCartIds = array_intersect($data['cart_item_ids'], $insufficientStockIds);
                        $redirectResponse = redirect()
                            ->route('checkout.show', ['cart_item_ids' => implode(',', $filteredCartIds) ])
                            ->with('errorStocks',$errorStocks);
                        return $redirectResponse;  // Dừng transaction callback
                    }
                }

                // Áp dụng voucher nếu có
                if (isset($data['voucher_code']) && Auth::check()) {
                    // dd(142412);
                    $voucher_result = $this->applyVoucher($data['voucher_code'], $totalPrice);
                    // if (isset($voucher_result['error'])) {
                    //     return response()->json(['message' => $voucher_result['error']], Response::HTTP_BAD_REQUEST);
                    // }
                    $totalPrice -= $voucher_result['discount_amount'];
                    $voucher = $voucher_result['voucher'];
                    // Cập nhật voucher_id và voucher_discount cho order
                    $order->update([
                        'voucher_id' => $voucher['id'],
                        'voucher_discount' => $voucher_result['discount_amount'],
                    ]);
                    Voucher::findOrFail($voucher['id'])->update([
                        'limit'=> $voucher['limit']-1,
                    ]);
                }
                $totalPrice +=30000;
                $order->update([
                    'total_quantity' => $totalQuantity,
                    'total' => max($totalPrice, $order->shipping_fee),
                ]);
                // Thực hiện thanh toán nếu chọn phương thức online (VNPay)
                if ($data['payment_method_id'] == 2) {
                    $payment = new PaymentController();
                    $response = $payment->createPayment($order);

                    // Chuyển hướng người dùng đến trang thanh toán
                    $redirectResponse = redirect($response['payment_url']);
                    return $redirectResponse;
                }
                $redirectResponse =  redirect('thank');
                
            });
            
            if ($redirectResponse) {
                return $redirectResponse;
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Hàm lấy thông tin người dùng
    protected function getUser($data)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $user = User::findOrFail($user_id)->load('addresses');
        } else {
            $user = [
                'id' => null,
                'name' => $data['ship_user_name'],
                'email' => $data['ship_user_email'],
                'address' => $data['ship_user_address'],
                'phone_number' => $data['ship_user_phonenumber'],
            ];
        }
        return $user;
    }

    // Hàm tạo đơn hàng
    protected function createOrder($data, $user)
    {
        // dd($user->toArray());
        return Order::create([
            'order_code' => "CHINI_SHOP". Str::upper(Str::random(6)),
            'user_id' => $user['id'],
            'payment_method_id' => $data['payment_method_id'],
            'total_quantity' => 0,
            'total' => 0.00,
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_phonenumber' => $user['addresses'][0]['phone'],
            'user_address' => $user['addresses'][0]['address'],
            'user_note' => $data['user_note'] ?? '',
            'ship_user_name' => $data['ship_user_name'],
            'ship_user_phonenumber' => $data['ship_user_phonenumber'],
            'ship_user_address' => $data['ship_user_address'],
            'shipping_fee' => $data['shipping_fee'],
            'voucher_id' => null,
            'voucher_discount' => 0,
        ]);
    }

    // Hàm thêm sản phẩm từ giỏ hàng vào đơn hàng
    protected function addCartItemsToOrder($data, $user, $order)
    {
        $cartItemIds = $data['cart_item_ids'];
        $errors = [
            'out_of_stock' => [], // Lỗi sản phẩm hết hàng
            'insufficient_stock' => [], // Lỗi số lượng không đủ
        ];
        $cart = Cart::query()
            ->where('user_id', $user['id'])
            ->with('cartitems.product', 'cartitems.productvariant.attributes')
            ->first();
        if (!$cart || $cart->cartitems->isEmpty()) {
            return redirect()->route('checkout.show')->with('error','Giỏ hàng trống, vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
        }
        $totalQuantity = 0;
        $totalPrice = 0.00;
        $validCartItemFound = false;
        foreach ($cart->cartitems as $cartItem) {
            if (in_array($cartItem->id, $cartItemIds)) {
                $product = $cartItem->product;
                $variant = $cartItem->productvariant;
                $validCartItemFound = true;
                $quantity = $cartItem->quantity;

                // Kiểm tra số lượng tồn kho của biến thể (nếu có)
                if ($variant) {
                    $availableQuantity = $variant->quantity;
                    $productPrice = $variant->price;
                } 
                
                // Nếu không còn sản phẩm trong kho
                if ($availableQuantity == 0) {
                    $errors['out_of_stock'][] = [
                        'message' => "Sản phẩm $product->name hiện đã hết hàng và hệ thống đã tự động loại bỏ khỏi giỏ hàng của bạn. Vui lòng kiểm tra và xác nhận lại đơn hàng.",
                        'product_id' => $product->id,
                        'cart_id' => $cartItem->id,
                    ];
                    // $cartItem->delete();
                    continue; // Bỏ qua sản phẩm này
                }
                // Nếu số lượng yêu cầu mua lớn hơn số lượng tồn kho, điều chỉnh số lượng và thông báo
                if ($quantity > $availableQuantity) {
                    $quantity = $availableQuantity; // Giảm số lượng về tối đa có thể mua
                    $errors['insufficient_stock'][] = [
                        'message' => "Số lượng sản phẩm $product->name trong kho không đủ. Bạn chỉ có thể mua tối đa $availableQuantity sản phẩm.",
                        'product_id' => $product->id,
                        'cart_id' => $cartItem->id,
                    ];
                }
                // Tạo chi tiết đơn hàng
                $attributes = $variant ? $variant->attributes->pluck('pivot.value', 'name')->toArray() : null;
               
                $this->createOrderDetail($order, $product, $variant, $productPrice, $quantity, $attributes);

                $totalQuantity += $quantity;
                $totalPrice += $productPrice * $quantity;

                // Giảm số lượng tồn kho
                if ($variant) {
                    $variant->decrement('quantity', $quantity);
                }
            }
        }

        // Nếu không tìm thấy sản phẩm hợp lệ trong giỏ hàng
        if (!$validCartItemFound) { // Rollback nếu có lỗi
            return redirect()->route('checkout.show')->with('error','Không có sản phẩm nào trong giỏ hàng phù hợp với yêu cầu của bạn.');
        }

        // Xóa các sản phẩm đã mua trong giỏ hàng
        CartItem::whereIn('id', $cartItemIds)->delete();
        return [$totalQuantity, $totalPrice, $errors];
    }

    // Hàm tạo chi tiết đơn hàng
    protected function createOrderDetail($order, $product, $variant, $price, $quantity, $attributes)
    {
        // dd($variant->toArray());
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'product_name' => $product->name,
            'product_img' => $variant->variant_image ?? '',
            'attributes' => $attributes,
            'quantity' => $quantity,
            'price' => $price,
            'total_price' => $price * $quantity,
            'discount' => 0,
        ]);
    }

    // Hàm áp dụng voucher call api
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_total' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where([
            ['code', '=', $request->code],
            ['is_active', '=', 1],
            ['limit', '>', 0],
            ['start_date', '<=', Carbon::now()],
            ['end_date', '>=', Carbon::now()],
        ])->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher không hợp lệ hoặc đã hết hạn'], 400);
        }

        // Kiểm tra giá trị tối thiểu đơn hàng
        if ($request->order_total < $voucher->min_order_value) {
            return response()->json(['message' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã này'], 400);
        }

        // Tính giảm giá
        $discountAmount = 0;
        if ($voucher->voucher_type === 'discount') {
            if ($voucher->discount_type === 'percent') {
                $discountAmount = $request->order_total * ($voucher->value / 100);
                if ($voucher->max_discount_value && $discountAmount > $voucher->max_discount_value) {
                    $discountAmount = $voucher->max_discount_value;
                }
            } elseif ($voucher->discount_type === 'amount') {
                $discountAmount = $voucher->value;
            }
        }
        

        // Trả về thông tin voucher đã áp dụng
        return response()->json([
            'message' => 'Áp dụng mã thành công',
            'discount_amount' => $discountAmount,
            'voucher' => [
                'code' => $voucher->code,
                'title' => $voucher->title,
                'discount_type' => $voucher->discount_type,
                'value' => $voucher->value,
                'max_discount_value' => $voucher->max_discount_value,
            ]
        ]);
    }

    //hàm áp dụng voucher trên server
    public function applyVoucher($code,$order_total)
    {
        $voucher = Voucher::where([
            ['code', '=', $code],
            ['is_active', '=', 1],
            ['limit', '>', 0],
            ['start_date', '<=', Carbon::now()],
            ['end_date', '>=', Carbon::now()],
        ])->first();

        // Tính giảm giá
        $discountAmount = 0;
        if ($voucher->voucher_type === 'discount') {
            if ($voucher->discount_type === 'percent') {
                $discountAmount = $order_total * ($voucher->value / 100);
                if ($voucher->max_discount_value && $discountAmount > $voucher->max_discount_value) {
                    $discountAmount = $voucher->max_discount_value;
                }
            } elseif ($voucher->discount_type === 'amount') {
                $discountAmount = $voucher->value;
            }
        }
        return [
            'discount_amount' => $discountAmount,
            'voucher' => [
                'id' =>$voucher->id,
                'limit' =>$voucher->limit,
                'code' => $voucher->code,
                'title' => $voucher->title,
                'discount_type' => $voucher->discount_type,
                'value' => $voucher->value,
                'max_discount_value' => $voucher->max_discount_value,
            ]
        ];
    }
    
    public function update(UpdateOrderRequest $request, Order $order)
    {
        try {
            // dd($request->all());
            $user_id = Auth::id();           

            // Kiểm tra trạng thái không hợp lệ
            if (in_array($order->order_status, [Order::STATUS_CANCELED, Order::STATUS_COMPLETED])) {
                return back()->with('error','Đơn hàng không thể cập nhật vì đã hoàn thành hoặc đã bị hủy.');
            }

            $order_status = $request->input('order_status');

            // Xử lý các trạng thái
            switch ($order_status) {
                case Order::STATUS_CANCELED:
                    if (!in_array($order->order_status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED])) {
                        // return response()->json([
                        //     'message' => 'Chỉ có thể hủy đơn hàng khi đơn hàng đang ở trạng thái Đang chờ xác nhận hoặc Đã xác nhận.'
                        // ], 400);
                        return back()->with('error','Chỉ có thể hủy đơn hàng khi đơn hàng đang ở trạng thái Đang chờ xác nhận hoặc Đã xác nhận.');
                    }

                    $user_note = $request->input('user_note');
                    $this->handleOrderCancellation($order, $user_note);

                    // Cập nhật trạng thái voucher nếu cần
                    // $voucher_logs = VoucherLog::query()
                    //     ->where('user_id', $user_id)
                    //     ->where('order_id', $order->id)
                    //     ->first();

                    // if ($voucher_logs) {
                    //     $voucher_logs->update(['action' => 'reverted']);
                    // }
                    $order->order_status = $order_status;
                    break;

                case Order::STATUS_COMPLETED:
                    if ($order->order_status !== Order::STATUS_SUCCESS) {
                        return back()->with('error','Chỉ có thể hoàn thành đơn hàng khi đơn hàng đang ở trạng thái giao hàng thành công.');
                        // return response()->json([
                        //     'message' => 'Chỉ có thể hoàn thành đơn hàng khi đơn hàng đang ở trạng thái giao hàng thành công.'
                        // ], 400);
                    }
                    $order->order_status = $order_status;
                    break;

                default:
                    // return response()->json(['message' => 'Trạng thái không hợp lệ.'], 400);
                    return back()->with('error' , 'Trạng thái không hợp lệ.');

            }

            $order->save();

            // broadcast(new OrderStatusUpdated($order))->toOthers();

            return back()->with('success','Hủy đơn hàng thành công');

        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi khi lấy thông tin đơn hàng', 'error' => $e->getMessage()], 500);
        }
    }
    protected function handleOrderCancellation(Order $order, string $user_note)
    {
        // Lưu lý do hủy vào ghi chú
        $order->return_notes = $user_note;
        // Trả lại số lượng sản phẩm về kho
        foreach ($order->orderDetails as $detail) {
            // Kiểm tra nếu là sản phẩm có biến thể
            if ($detail->variant_id) {
                $variant = Variant::find($detail->variant_id);
                if ($variant) {
                    $variant->increment('quantity', $detail->quantity);
                }
            } else {
                // Nếu là sản phẩm đơn
                $product = Product::find($detail->product_id);
                if ($product) {
                    $product->increment('quantity', $detail->quantity);
                }
            }
        }
    }

    //  protected function handleOrderCancellation(Request $request)
    // {
    //     // dd($request->all(),$order->toArray());
    //     $order = Order::findOrFail($request->order_id)->load('orderDetails');
    //     // Lưu lý do hủy vào ghi chú
    //     $order->return_notes = $request->reason;
    //     // Trả lại số lượng sản phẩm về kho
    //     foreach ($order->orderDetails as $detail) {
    //         // Kiểm tra nếu là sản phẩm có biến thể
    //         if ($detail->product_variant_id) {
    //             $variant = Variant::find($detail->product_variant_id);
    //             if ($variant) {
    //                 $variant->increment('quantity', $detail->quantity);
    //             }
    //         }
    //         //  else {
    //         //     // Nếu là sản phẩm đơn
    //         //     $product = Product::find($detail->product_id);
    //         //     if ($product) {
    //         //         $product->increment('quantity', $detail->quantity);
    //         //     }
    //         // }
    //     }
    //     $order->update();
    //     return back()->with('success','Hủy đơn hàng thành công');
    // }
    public function searchOrder(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'type' => 'required|in:phoneNumber,email',
            'contact' => ['required', function ($attribute, $value, $fail) use ($request) {
                if ($request->type === 'phoneNumber') {
                    // Validate số điện thoại Việt Nam
                    if (!preg_match('/^(0|\+84)[3|5|7|8|9][0-9]{8}$/', $value)) {
                        $fail('Số điện thoại không hợp lệ.');
                    }
                } elseif ($request->type === 'email') {
                    // Validate email
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('Email không hợp lệ.');
                    }
                }
            }],
            'order_code' => 'required_if:type,email|string',
        ]);

        // Nếu type là email, tìm đơn hàng với order_code và email
        if ($request->type == "email") {
            $order = Order::where('order_code', $request->order_code)
                ->where('user_email', $request->contact)
                ->first();
        }

        // Nếu type là phoneNumber, tìm đơn hàng với số điện thoại
        if ($request->type == "phoneNumber") {
            $order = Order::query()->with('orderDetails')
                ->where('ship_user_phonenumber', $request->contact)->latest('id')
                ->get();
        }
        // Nếu không tìm thấy đơn hàng
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy đơn hàng, thông tin bạn cung cấp không đúng!',
            ], 400);
        }

        // Nếu type là email, kiểm tra OTP
        if ($request->type == "email") {
            // Kiểm tra xem người dùng đã yêu cầu OTP trước đó chưa
            $existingOtp = DB::table('order_otp_verifications')
                ->where('contact', $request->contact)
                ->orderBy('expires_at', 'desc')
                ->first();

            // Nếu đã có OTP trước đó và OTP chưa hết hạn
            if ($existingOtp && Carbon::parse($existingOtp->expires_at)->isFuture()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bạn đã yêu cầu mã OTP trước đó. Vui lòng đợi cho đến khi mã OTP cũ hết hiệu lực.',
                ], 400);
            }

            // Tạo mã OTP ngẫu nhiên
            $otpCode = rand(100000, 999999);
            $otpExpiresAt = Carbon::now()->addMinutes(3); // Mã OTP hết hạn sau 3 phút

            // Lưu mã OTP và thời gian hết hạn vào bảng order_otp_verifications
            DB::table('order_otp_verifications')->insert([
                'order_code' => $request->order_code ?? '',
                'contact' => $request->contact,
                'otp' => $otpCode,
                'expires_at' => $otpExpiresAt,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Đẩy việc gửi email OTP vào queue
            SendOtpEmail::dispatch($request->contact, $otpCode);

            return response()->json([
                'status' => 'success',
                'message' => 'Mã OTP đã được gửi đến email của bạn.',
            ], 200);
        }

        // Trả về thông tin đơn hàng nếu không cần xác minh OTP
        return response()->json([
            'status' => 'success',
            'order' => $order,
        ], 200);
    }
    public function verifyOtp(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'contact' => 'required|email',
            'order_code' => 'required|string',
            'otp' => 'required|string',
        ]);

        // Kiểm tra mã OTP trong bảng order_otp_verifications
        $otpVerification = DB::table('order_otp_verifications')
            ->where('order_code', $request->order_code)
            ->where('contact', $request->contact)
            ->where('otp', $request->otp)
            ->first();

        // Nếu không tìm thấy OTP hoặc OTP đã hết hạn
        if (!$otpVerification || Carbon::now()->gt(Carbon::parse($otpVerification->expires_at))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn.',
            ], 400);
        }

        // Tìm đơn hàng sau khi xác minh OTP thành công
        $order = Order::query()
            ->with('orderDetails')
            ->where("order_code", "=", $otpVerification->order_code)
            ->first();

        // Xác minh OTP thành công
        return response()->json([
            'status' => 'success',
            'order' => $order,
        ], 200);
    }
    // thanh toán lại(chưa xong)
    function handlePayment(Request $request)
    {
        try {
            // Validate orderId là số và tồn tại trong bảng orders
            $request->validate([
                'orderId' => 'required|numeric|exists:orders,id',
            ]);
            $user_id = auth('sanctum')->id() ?? null;

            // Tìm đơn hàng dựa vào ID
            $order = Order::query()
                ->where('user_id', $user_id)
                ->where('id', $request->orderId)
                ->where('payment_method_id', 2)
                ->where('payment_status', Order::PAYMENT_PENDING)
                ->first();
            // Kiểm tra xem đơn hàng có tồn tại không
            if (!$order) {
                return response()->json([
                    'message' => 'Đơn hàng không tồn tại hoặc không thể xử lý thanh toán.'
                ], Response::HTTP_NOT_FOUND);
            }
            // Xử lý thanh toán COD
            $order->update([
                'payment_method_id' => 1, // Cập nhật phương thức thanh toán thành COD
            ]);

            return response()->json([
                'message' => 'Phương thức thanh toán đã được chuyển sang COD.',
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}