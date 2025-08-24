<?php

namespace App\Http\Controllers\Admin;


use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Models\Variant;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission.404:order-list')->only('index', 'show');
        // $this->middleware('permission.404:order-create')->only('create', 'store');
        // $this->middleware('permission.404:order-edit')->only('edit', 'update');
        // $this->middleware('permission.404:order-delete')->only('destroy');
        $this->middleware('permission.404:crudorder')->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    // dd(13321);
    public function index()
    {
        try {
            Order::where('order_status', 'Giao hàng thành công')
                ->whereDate('updated_at', '<', now()->subDays(3))
                ->update(['order_status' => 'Hoàn thành']);
            $orders = Order::with('orderDetails')
                ->latest()
                ->paginate(10);
            return view('admin.pages.orders.index', compact('orders'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::info('Fetching order with ID: ' . $id);
        try {
            // Lấy thông tin đơn hàng từ bảng Order
            $order = Order::findOrFail($id);
            $orderDetails = $order->orderDetails()->get();
            // Kết hợp thông tin đơn hàng với các chi tiết
            $orderData = [
                'order' => $order->toArray(),
                'order_details' => $orderDetails->toArray(),
            ];
            // dd($orderData);
            return view('admin.pages.orders.show', [
                'order' => $orderData['order'],
                'order_details' => $orderData['order_details'],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Order not found: ' . $e->getMessage());
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error fetching order: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
           

            $order = Order::findOrFail($id);
           
            $currentStatus = $order->order_status;
            $newStatus = $request->input('order_status');

            $statusMap = [
                'Đang chờ xác nhận' => 1,
                'Đã xác nhận' => 2,
                'Đã hủy' => 3,
                'Đang vận chuyển' => 4,
                'Giao hàng thành công' => 5,
                'Yêu cầu hoàn trả hàng' => 6,
                'Hoàn trả hàng' => 7,
                'Hoàn thành' => 8,
                'Đã nhận hàng' => 9,
            ];


            if (is_numeric($newStatus) && in_array((int)$newStatus, $statusMap)) {
                $newStatus = array_search((int)$newStatus, $statusMap);
            }

            // Kiểm tra trạng thái đơn hàng
            if (!array_key_exists($newStatus, $statusMap)) {
                return response()->json(['message' => 'Trạng thái không hợp lệ.'], Response::HTTP_BAD_REQUEST);
            }

            if ($currentStatus === 'Đã hủy' || $currentStatus === 'Hoàn thành' || $currentStatus === 'Hoàn trả hàng' || $currentStatus === 'Yêu cầu hoàn trả hàng') {
                return response()->json(['message' => "Không thể thay đổi trạng thái \"$currentStatus\"."], Response::HTTP_BAD_REQUEST);
            }


            if ($currentStatus === 'Đang chờ xác nhận' && !in_array($newStatus, ['Đã xác nhận', 'Đã hủy'])) {
                return response()->json(['message' => 'Trạng thái tiếp theo chỉ có thể là "Đã xác nhận" hoặc "Đã hủy".'], Response::HTTP_BAD_REQUEST);
            }
            if ($currentStatus === 'Đang chờ xác nhận' && $newStatus === 'Đã xác nhận') {
                // Kiểm tra nếu phương thức thanh toán là 2 và payment_status là 'Chưa thanh toán'
                if ($order->payment_method_id == 2 && $order->payment_status == 'Chưa Thanh Toán') {
                    // Cập nhật payment_method_id thành 1
                    $order->payment_method_id = 1;
                }
            }

            if ($currentStatus === 'Đã xác nhận' && !in_array($newStatus, ['Đang vận chuyển', 'Đã hủy'])) {

                return response()->json(['message' => 'Trạng thái tiếp theo chỉ có thể là "Đang vận chuyển" hoặc "Đã hủy".'], Response::HTTP_BAD_REQUEST);
            }

            if ($currentStatus === 'Đang vận chuyển' && !in_array($newStatus, ['Giao hàng thành công'])) {
                return response()->json(['message' => 'Khi đang vận chuyển, chỉ có thể cập nhật thành "Giao hàng thành công".'], Response::HTTP_BAD_REQUEST);
            }
            if ($currentStatus === 'Giao hàng thành công' && !in_array($newStatus, ['Đã nhận hàng', 'Hoàn thành'])) {
                return response()->json(['message' => 'Sau "Giao hàng thành công", chỉ có thể chuyển sang "Hoàn thành".'], Response::HTTP_BAD_REQUEST);
            }

          
            if ($newStatus === 'Đã nhận hàng') {
                $newStatus = 'Hoàn thành';
            }



            // Nếu có trạng thái mới từ request, thực hiện thay đổi trạng thái
            if ($newStatus && $newStatus !== $order->order_status) {
                $order->order_status = $newStatus;
            }
            if ($newStatus === 'Đã hủy') {
                // Gọi logic hoàn lại sản phẩm về kho
                $this->handleOrderCancellation($order, null); // Không cần lý do hủy
            }
            if ($newStatus === 'Giao hàng thành công') {
                $order->payment_status = 'Đã thanh toán';
            }
            $order->save();
            

            return response()->json(['message' => 'Cập nhật trạng thái đơn hàng thành công.', 'order' => $order], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    protected function handleOrderCancellation(Order $order, ?string $user_note = null)
    {
        // Lưu lý do hủy vào ghi chú, nếu không có thì đặt giá trị mặc định
        $order->return_notes = $user_note ?? 'Không có lý do được cung cấp';

        // Trả lại số lượng sản phẩm về kho
        foreach ($order->orderDetails as $detail) {
            // Kiểm tra nếu là sản phẩm có biến thể
            if ($detail->product_variant_id) {
                $variant = Variant::find($detail->product_variant_id);
                if ($variant) {
                    $variant->increment('quantity', $detail->quantity);
                }
            }
            
        }
        $order->save();
    }

}
