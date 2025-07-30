<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\CartItem;
use App\Models\VoucherMeta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\Product\GetUniqueAttribute;
use App\Http\Requests\Checkout\StoreCheckoutRequest;
use App\Models\VoucherUser;

class CheckoutController extends Controller
{
    public function store(StoreCheckoutRequest $request)
    {
        try {
            $data = $request->validated(); // Lấy dữ liệu đã xác thực

            // Kiểm tra xem người dùng có muốn mua từ giỏ hàng hay không
            $isCartPurchase = isset($data['cart_item_ids']) && is_array($data['cart_item_ids']) && count($data['cart_item_ids']) > 0;

            // Khởi tạo biến cho tổng tiền và danh sách sản phẩm
            $sub_total = 0;
            $total_items = 0;
            $order_items = [];
            $errors = [];
            // $voucher_discount = 0;

            // Kiểm tra thông tin người dùng nếu đã đăng nhập
            if (Auth::check()) {
                $user_id = Auth::id();
                $user = User::with('addresses')->findOrFail($user_id)->makeHidden(['email_verified_at', 'password', 'remember_toke']);
                if ($isCartPurchase) {
                    $errors = [
                        'out_of_stock' => [], // Lỗi sản phẩm hết hàng
                        'insufficient_stock' => [], // Lỗi số lượng không đủ
                    ];
                    $cart = Cart::query()
                        ->where('user_id', $user['id'])
                        ->with([
                            "cartitems" => function ($query) use ($data) {
                                $query->whereIn('id', $data['cart_item_ids']);
                            },
                            "cartitems.product",
                            "cartitems.productvariant.attributes"
                        ])
                        ->first();

                    if (!$cart || $cart->cartitems->isEmpty()) {
                        return response()->json(['message' => 'Sản phẩm không tồn tại trong giỏ hàng'], 400);
                    }

                    // Kiểm tra nếu có sản phẩm nào không tồn tại trong giỏ hàng
                    $invalid_items = array_diff($data['cart_item_ids'], $cart->cartitems->pluck('id')->toArray());

                    if (!empty($invalid_items)) {
                        return response()->json([
                            'message' => 'Sản phẩm không tồn tại trong giỏ hàng',
                            'invalid_items' => $invalid_items // Gửi danh sách sản phẩm không hợp lệ để người dùng biết
                        ], 400);
                    }
                    foreach ($cart->cartitems as $cart_item) {
                        $quantity = $cart_item->quantity;
                        $variant = $cart_item->productvariant;
                        $product = $cart_item->product;

                        // Kiểm tra tồn kho và giá theo loại sản phẩm (variant hoặc đơn giản)
                        // $price = $variant ? $variant->price_sale : $product->price_sale;
                        $available_quantity = $variant ? $variant->quantity : $product->quantity;
                        // dd($variant->toArray());

                        // Xử lý trường hợp hết hàng
                        if ($available_quantity == 0) {
                            $errors['out_of_stock'][] = [
                                'message' => "Sản phẩm {$product->name} hiện đã hết hàng và hệ thống đã tự động loại bỏ khỏi giỏ hàng của bạn. Vui lòng kiểm tra và xác nhận lại đơn hàng.",
                                'product_id' => $product->id,
                                'cart_id' => $cart_item->id,
                            ];
                            $cart_item->delete();
                            if ($variant) {
                                $tongSoLuong = $product->variants->sum('quantity');
                                if ($tongSoLuong <= 0) {
                                    $product->update(["status" => false]);
                                }
                            } else {
                                $product->update(["status" => false]);
                            }
                            continue;
                        }
                        // Xử lý trường hợp số lượng không đủ
                        if ($quantity > $available_quantity) {
                            $quantity = $available_quantity;
                            $errors['insufficient_stock'][] = [
                                'message' => "Số lượng sản phẩm {$product->name} trong kho không đủ. Bạn chỉ có thể mua tối đa $available_quantity sản phẩm.",
                                'product_id' => $product->id,
                                'cart_id' => $cart_item->id,
                                'max_quantity'=>$available_quantity
                            ];
                            $cart_item->update(['quantity' => $available_quantity]);
                        }
                        // dd($errors);

                        // Tính toán giá trị sản phẩm và thêm vào danh sách đơn hàng
                        $total_price = $variant->price * $quantity;
                        $sub_total += $total_price;
                        $order_items[] = [
                            // 'errors' => $errors ?? [],
                            'quantity' => $quantity,
                            'total_price' => $total_price,
                            'product' => $product,
                            'variant' => $variant,
                        ];

                        $total_items += 1;
                    }
                } 
            }

            if (empty($errors['out_of_stock']) && empty($errors['insufficient_stock'])) {
                return response()->json([
                    "user" => Auth::check()  ? $user : null,
                    "sub_total" => $sub_total,
                    "total_items" => $total_items,
                    "order_items" => $order_items,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'errors' => $errors,
                    "sub_total" => $sub_total,
                    "total_items" => $total_items,
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $ex) {
            return response()->json(["message" => $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function create(){
        return view('client.pages.checkout');
    }

}