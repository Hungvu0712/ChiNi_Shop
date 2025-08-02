@extends('admin.layouts.master')
@section('title', 'Chi tiết đơn hàng')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
    </style>
@endsection
@section('content')
    <div class="container py-5">
        <h3 class="text-center fw-bold mb-4">Chi tiết Đơn hàng</h3>

        <!-- Thông tin đơn hàng -->
        <div class="card p-3 mb-4">
            <div class="section-title fw-bold mb-2">🧾 Thông tin Đơn hàng</div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mã đơn hàng:</strong> {{ $order['order_code'] }}</p>
                    <p><strong>Giảm:</strong> {{ number_format($order['voucher_discount'], 0, ',', '.') }} ₫</p>
                    <p><strong>Tổng số lượng:</strong> {{ $order['total_quantity'] }}</p>
                    <p><strong>Phương thức thanh toán:</strong>
                        @if ($order['payment_method_id'] == 1)
                            Thanh toán khi nhận hàng
                        @elseif ($order['payment_method_id'] == 2)
                            Chuyển khoản ngân hàng
                        @else
                            Khác
                        @endif
                    </p>
                    <p><strong>Giao đến:</strong> {{ $order['ship_user_address'] }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Trạng thái:</strong> {{ $order['order_status'] }}</p>
                    <p><strong>Ngày đặt hàng:</strong> {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') }}</p>
                    <p><strong>Tổng tiền:</strong> {{ number_format($order['total'], 0, ',', '.') }} ₫</p>
                    <p><strong>Trạng thái thanh toán:</strong> {{ $order['payment_status'] }}</p>
                </div>
            </div>
        </div>

        <!-- Người nhận hàng -->
        <div class="card p-3 mb-4">
            <div class="section-title fw-bold mb-2">👤 Thông tin khách hàng</div>
            <p><strong>Tên:</strong> {{ $order['user_name'] }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order['user_phonenumber'] }}</p>
            <p><strong>Email:</strong> {{ $order['user_email'] }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order['user_address'] }}</p>
        </div>

        <!-- Người giao hàng -->
        <div class="card p-3 mb-4">
            <div class="section-title fw-bold mb-2">📦 Thông tin người nhận hàng</div>
            <p><strong>Tên:</strong> {{ $order['ship_user_name'] }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order['ship_user_phonenumber'] }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order['ship_user_address'] }}</p>
        </div>

        <!-- Sản phẩm trong đơn hàng -->
        <div class="card p-3 mb-4">
            <div class="section-title fw-bold mb-2">🛍️ Sản phẩm trong đơn hàng</div>
            @foreach ($order_details as $detail)
                <div class="d-flex align-items-start gap-3 mb-3 border-bottom pb-2">
                    <img src="{{ $detail['product_img'] }}" alt="Product" class="product-img" style="width: 80px; height: 80px; object-fit: cover;">
                    <div>
                        <p class="mb-1"><strong>{{ $detail['product_name'] }}</strong></p>
                        <p class="mb-1">
                            Phân loại sản phẩm:
                            @foreach ($detail['attributes'] as $key => $value)
                                {{ $key }}: {{ $value }}@if (!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="mb-1">Số lượng: {{ $detail['quantity'] }}</p>
                        <p class="mb-1">Giá: {{ number_format($detail['price'], 0, ',', '.') }} ₫</p>
                        <p class="mb-1">Thành tiền: {{ number_format($detail['total_price'], 0, ',', '.') }} ₫</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
@endsection