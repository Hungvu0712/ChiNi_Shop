@extends('client.layouts.master')
@section('title', 'Orders')
@section('css')
    <style>
        .header01 {
            background-color: #ecf5f4;
            font-family: "Segoe UI", sans-serif;
        }

        .order-card {
            border-radius: 8px;
            border: 1px solid #eee;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .order-header {
            padding: 12px 20px;
            background-color: #f8fafc;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
        }

        .order-status {
            color: #00bcd4;
            margin-left: 10px;
        }

        .product-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-info {
            flex: 1;
            margin-left: 15px;
        }

        .product-info small {
            display: block;
            color: #666;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            align-items: center;
        }

        .total-price {
            color: red;
            font-weight: bold;
        }

        /* Modal header fix */
        .modal-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #ddd;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
        }

        .order-detail-label {
            font-weight: 500;
            white-space: nowrap;
        }

        .order-detail-value {
            text-align: right;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .container {
            margin-top: 100px;
        }
    </style>
    <!-- Bootstrap 5 -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container py-5">
        <h4 class="mb-4">📦 Quản lý đơn hàng của bạn</h4>

        @foreach ($orders as $order)
            <div class="order-card shadow-sm mb-4">
                <div class="order-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-chevron-down"></i>
                        <strong>{{ $order->order_code }}</strong>
                        <span class="order-status">| {{ strtoupper($order->order_status) }}</span>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#orderModal{{ $order->id }}">
                        Xem chi tiết
                    </button>
                </div>

                @foreach ($order->orderDetails as $item)
                    <div class="product-item">
                        <img src="{{ $item->product_img }}" alt="{{ $item->product_name }}" width="70" height="70">
                        <div class="product-info">
                            <div><strong>{{ $item->product_name }}</strong></div>
                            @foreach ($item->attributes as $key => $value)
                                <small><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</small>
                            @endforeach
                            <small>x {{ $item->quantity }}</small>
                        </div>
                        <div>{{ number_format($item->price, 0, ',', '.') }}₫</div>
                    </div>
                @endforeach

                <div class="order-footer d-flex justify-content-between align-items-center mt-2">
                    @if (in_array($order->order_status, [\App\Models\Order::STATUS_PENDING, \App\Models\Order::STATUS_CONFIRMED]))
                        <button class="btn btn-info text-white btn-sm" data-bs-toggle="modal"
                            data-bs-target="#orderCancel{{ $order->id }}">
                            Xác nhận hủy
                        </button>
                    @endif

                    <div>Thành tiền: <span
                            class="total-price text-danger">{{ number_format($order->total, 0, ',', '.') }}₫</span></div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1"
                aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">
                                <strong>{{ $order->order_code }}</strong>
                                <span class="text-info">| {{ strtoupper($order->order_status) }}</span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-3 text-muted">Thời gian đặt hàng:
                                        {{ $order->created_at->format('d-m-Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-3 text-danger fw-bold">Trạng thái đơn hàng: {{ $order->payment_status }}
                                    </p>
                                </div>
                            </div>

                            @foreach ($order->orderDetails as $item)
                                <div class="product-item bg-light rounded mb-2">
                                    <img src="{{ $item->product_img }}" alt="{{ $item->product_name }}" width="70"
                                        height="70">
                                    <div class="product-info">
                                        <div><strong>{{ $item->product_name }}</strong></div>
                                        @foreach ($item->attributes as $key => $value)
                                            <small><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</small>
                                        @endforeach
                                        <small>x {{ $item->quantity }}</small>
                                    </div>
                                    <div>{{ number_format($item->price, 0, ',', '.') }}₫</div>
                                </div>
                            @endforeach

                            <table class="table mt-4">
                                <tbody>
                                    <tr>
                                        <td class="order-detail-label">Thông tin khách hàng</td>
                                        <td class="order-detail-value">{{ $order->user_name }} -
                                            {{ $order->user_phonenumber }}</td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Địa chỉ</td>
                                        <td class="order-detail-value">{{ $order->user_address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Ghi chú</td>
                                        <td class="order-detail-value">{{ $order->user_note }}</td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Phí vận chuyển</td>
                                        <td class="order-detail-value">
                                            {{ number_format($order->shipping_fee, 0, ',', '.') }}₫
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Khuyến mãi</td>
                                        <td class="order-detail-value">
                                            {{ number_format($order->voucher_discount, 0, ',', '.') }} ₫</td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Thành tiền</td>
                                        <td class="order-detail-value text-danger fw-bold">
                                            {{ number_format($order->total, 0, ',', '.') }}₫
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Phương thức thanh toán</td>
                                        <td class="order-detail-value">{{ $order->paymentMethod->name ?? 'Không rõ' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Trạng thái</td>
                                        <td class="order-detail-value">{{ $order->order_status }}</td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Thông tin người nhận hàng</td>
                                        <td class="order-detail-value">{{ $order->ship_user_name }} -
                                            {{ $order->ship_user_phonenumber }}</td>
                                    </tr>
                                    <tr>
                                        <td class="order-detail-label">Địa chỉ nhận hàng</td>
                                        <td class="order-detail-value">{{ $order->ship_user_address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- modal form hủy đơn hàng --}}
            <!-- Modal Hủy Đơn -->
            <div class="modal fade" id="orderCancel{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Lý do hủy đơn hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="cancelOrderForm" method="post"
                                action="{{ route('order.update', ['order' => $order->id]) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="order_status" value="{{ \App\Models\Order::STATUS_CANCELED }}">
                                <div class="mb-3">
                                    <textarea class="form-control" name="user_note" id="cancel_reason" placeholder="Nhập lý do hủy..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2"
                                        data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Gửi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {{ $orders->links() }}
    </div>
@endsection

@section('script')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
