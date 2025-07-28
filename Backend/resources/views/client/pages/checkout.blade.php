@extends('client.layouts.master')
@section('title', 'Thanh toán')
@section('css')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .section-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .checkout-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .product-image {
            width: 60px;
            height: 80px;
            object-fit: cover;
        }

        .text-red {
            color: red;
        }
    </style>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
@section('content')
    <!-- BEGIN: Page Banner Section -->
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Thanh toán</h2>
                        <div class="pageBannerPath">
                            <a href="index.html">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Thanh toán</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Page Banner Section -->
    <!-- BEGIN: Checkout Page Section -->
    <section class="checkoutPage">
        <div class="container">
            <div class="row">
                <!-- Thông tin người dùng & địa chỉ -->
                <div class="col-md-7">
                    <div class="checkout-box mb-3">
                        <div class="section-title">🧍‍♂️ THÔNG TIN NGƯỜI DÙNG ✓</div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tên người dùng:</strong> Mạnh Cường</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Số điện thoại:</strong> 0987654321</p>
                            </div>
                        </div>


                    </div>

                    <div class="checkout-box">
                        <div class="section-title">📍 ĐỊA CHỈ NHẬN HÀNG ✓</div>
                        <div class="mb-3">
                            <label class="form-label">Thành phố</label>
                            <select class="form-select">
                                <option selected>Chọn thành phố</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quận huyện</label>
                            <select class="form-select">
                                <option selected>Chọn quận huyện</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phường xã</label>
                            <select class="form-select">
                                <option selected>Chọn phường xã</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ cụ thể</label>
                            <input type="text" class="form-control" value="hà nội">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" class="form-control" placeholder="Ghi chú">
                        </div>

                        <div class="section-title">Phương thức thanh toán</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="radio" name="payment" id="cod" checked>
                                    <label class="form-check-label d-flex align-items-center" for="cod">
                                        <i class="bi bi-truck me-2"></i> Thanh toán khi nhận hàng
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="radio" name="payment" id="online">
                                    <label class="form-check-label d-flex align-items-center" for="online">
                                        <i class="bi bi-credit-card me-2"></i> Thanh toán online
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Đặt hàng -->
                <div class="col-md-5">
                    <div class="checkout-box">
                        <div class="section-title">🛒 Đặt hàng</div>
                        <div class="d-flex align-items-start mb-3">
                            <img src="https://via.placeholder.com/60x80" class="product-image me-3" alt="Product">
                            <div>
                                <div><strong>Áo len gilet nữ cổ tim phối họa tiết</strong></div>
                                <small>m / green / len</small><br>
                                <small>Số lượng: 1</small><br>
                                <strong class="text-red">600.000 đ</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Voucher">
                                <button class="btn btn-outline-primary">Áp dụng</button>
                                <button class="btn btn-outline-secondary">Hủy</button>
                            </div>
                        </div>

                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between">
                                <span>Voucher:</span>
                                <span>0 đ</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Phí ship:</span>
                                <span>0 đ</span>
                            </li>
                            <li class="d-flex justify-content-between fw-bold fs-5">
                                <span>Tổng tiền:</span>
                                <span>600.000 đ</span>
                            </li>
                        </ul>

                        <button class="btn btn-info w-100 text-white">Xác nhận đơn hàng</button>
                        <small class="d-block mt-2 text-center">
                            <a href="#">Tìm hiểu thêm thông tin về thuế và vận chuyển</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Checkout Page Section -->

@endsection
@section('script')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection