@extends('client.layouts.master')
@section('title', 'Cảm ơn')
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
        .container{
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
        <!-- Order Card -->
        <div class="order-card shadow-sm">
            <div class="order-header">
                <div>
                    <i class="bi bi-chevron-down"></i>
                    <strong>MIXMATCH-6885827BE4F35</strong>
                    <span class="order-status">| ĐÃ HỦY</span>
                </div>
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal">
                    Xem chi tiết
                </button>
            </div>

            <!-- Product List -->
            <div class="product-item">
                <img src="https://via.placeholder.com/70x70" alt="áo len nam">
                <div class="product-info">
                    <div><strong>Áo len nam cổ tròn</strong></div>
                    <small>s | black</small>
                    <small>x 2</small>
                </div>
                <div>300.000₫</div>
            </div>
            <div class="product-item">
                <img src="https://via.placeholder.com/70x70" alt="áo len nữ">
                <div class="product-info">
                    <div><strong>Áo len nữ cổ lọ dệt loang</strong></div>
                    <small>m | orange</small>
                    <small>x 2</small>
                </div>
                <div>400.000₫</div>
            </div>

            <!-- Footer -->
            <div class="order-footer">
                <button class="btn btn-info text-white btn-sm">Mua Lại</button>
                <div>Thành tiền: <span class="total-price">1.400.000₫</span></div>
            </div>
        </div>
    </div>

    <!-- Modal Chi tiết đơn -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">
                        MÃ ĐƠN HÀNG · MIXMATCH-6885827BE4F35
                        <span class="text-info">| ĐÃ HỦY</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3 text-muted">Thời gian đặt hàng: 2025-07-27 01:53:55</p>

                    <!-- Product list -->
                    <div class="product-item bg-light rounded">
                        <img src="https://via.placeholder.com/70x70" alt="">
                        <div class="product-info">
                            <div><strong>Áo len nam cổ tròn</strong></div>
                            <small>s | black</small>
                            <small>x 2</small>
                        </div>
                        <div>300.000₫</div>
                    </div>
                    <div class="product-item bg-light rounded mt-2">
                        <img src="https://via.placeholder.com/70x70" alt="">
                        <div class="product-info">
                            <div><strong>Áo len nữ cổ lọ dệt loang</strong></div>
                            <small>m | orange</small>
                            <small>x 2</small>
                        </div>
                        <div>400.000₫</div>
                    </div>

                    <!-- Order details -->
                    <table class="table mt-4">
                        <tbody>
                            <tr>
                                <td class="order-detail-label">Thông tin</td>
                                <td class="order-detail-value">Mạnh Cường - 0987654321</td>
                            </tr>
                            <tr>
                                <td class="order-detail-label">Địa chỉ nhận hàng</td>
                                <td class="order-detail-value">Hà Nội, Xã Nhật Quang, Huyện Phù Cừ, Hưng Yên</td>
                            </tr>
                            <tr>
                                <td class="order-detail-label">Phí vận chuyển</td>
                                <td class="order-detail-value">39.000₫</td>
                            </tr>
                            <tr>
                                <td class="order-detail-label">Khuyến mãi</td>
                                <td class="order-detail-value">0₫</td>
                            </tr>
                            <tr>
                                <td class="order-detail-label">Thành tiền</td>
                                <td class="order-detail-value text-danger fw-bold">1.400.000₫</td>
                            </tr>
                            <tr>
                                <td class="order-detail-label">Phương thức thanh toán</td>
                                <td class="order-detail-value">COD - Nhận hàng thanh toán</td>
                            </tr>
                            <tr>
                                <td class="order-detail-label">Trạng thái</td>
                                <td class="order-detail-value">Chưa thanh toán</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection