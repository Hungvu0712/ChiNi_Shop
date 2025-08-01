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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            {{-- @dd($error) --}}
                            @if (is_array($error))
                                <li>{{ $error['message'] }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                @if (is_array($data))
                    @foreach ($data as $id)
                        <input type="hidden" name="cart_item_ids[]" value="{{ $id }}">
                    @endforeach
                @endIf
                <div class="row">
                    <!-- Thông tin người dùng & địa chỉ -->
                    <div class="col-md-7">
                        <div class="checkout-box mb-3">
                            <div class="section-title">🧍‍♂️ THÔNG TIN NGƯỜI DÙNG ✓</div>

                            <div class="mb-3">
                                <label for="user_name" class="form-label">Tên người dùng</label>
                                <input type="text" class="form-control" readonly name="user_name" id="user_name"
                                    value="{{ $user['addresses'][0]['fullname'] }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="user_email" class="form-label">Email</label>
                                <input type="email" class="form-control" readonly name="user_email" id="user_email"
                                    value="{{ $user['email'] }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="user_phonenumber" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" readonly name="user_phonenumber"
                                    id="user_phonenumber" value="{{ $user['addresses'][0]['phone'] }}" required>
                            </div>

                            {{-- <div class="mb-3">
                                <label for="user_address" class="form-label">Địa chỉ</label>
                                <textarea class="form-control" readonly name="user_address" id="user_address" rows="2" required>{{ $user['addresses'][0]['specific_address'] }}</textarea>
                            </div> --}}

                            {{-- <div class="mb-3">
                                <label for="user_note" class="form-label">Ghi chú</label>
                                <textarea class="form-control" name="user_note" id="user_note" rows="2" placeholder="Ghi chú">{{ $user['addresses'][0]['note'] }}</textarea>
                            </div> --}}
                        </div>

                        <div class="checkout-box">
                            <div class="section-title">📍 ĐỊA CHỈ GIAO HÀNG ✓</div>
                            <div class="mb-3">
                                <label for="ship_user_name" class="form-label">Tên người nhận</label>
                                <input type="text" class="form-control" name="ship_user_name" id="ship_user_name"
                                    value="{{ $user['addresses'][0]['fullname'] }}">
                                @error('ship_user_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ship_user_phonenumber" class="form-label">Số điện thoại người nhận</label>
                                <input type="text" class="form-control" name="ship_user_phonenumber"
                                    id="ship_user_phonenumber" value="{{ $user['addresses'][0]['phone'] }}">
                                @error('ship_user_phonenumber')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ship_user_address" class="form-label">Địa chỉ người nhận</label>
                                <textarea class="form-control" name="ship_user_address" id="ship_user_address" rows="2">{{ $user['addresses'][0]['specific_address'] }}</textarea>
                                @error('ship_user_address')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="user_note" class="form-label">Ghi chú</label>
                                <textarea class="form-control" name="user_note" id="user_note" rows="2" placeholder="Ghi chú">{{ $user['addresses'][0]['note'] }}</textarea>
                                @error('user_note')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="section-title">Phương thức thanh toán</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2 d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="radio" name="payment_method_id"
                                            id="cod" value="1" checked>
                                        <label class="form-check-label d-flex align-items-center" for="cod">
                                            <i class="bi bi-truck me-2"></i> Thanh toán khi nhận hàng
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="radio" name="payment_method_id"
                                            id="online" value="2">
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
                            @foreach ($order_items as $order_item)
                                <div class="d-flex align-items-start mb-3">
                                    <img src="{{ $order_item['variant']['variant_image'] }}" class="product-image me-3"
                                        alt="{{ $order_item['product']['name'] }}">
                                    <div>
                                        <div><strong>{{ $order_item['product']['name'] }}</strong></div>
                                        @foreach ($order_item['variant']['attributes'] as $attributeName)
                                            <small>{{ $attributeName['name'] }}:
                                                {{ $attributeName['pivot']['value'] }}</small><br>
                                        @endforeach
                                        <small>Số lượng: {{ $order_item['quantity'] }}</small><br>
                                        <div><strong class="text-red">Giá tiền:
                                                {{ number_format($order_item['variant']['price']) }} đ</strong></div>
                                        <strong class="text-red">Thành tiền:
                                            {{ number_format($order_item['total_price']) }} đ</strong>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mb-3">
                                <label for="voucher_id" class="form-label">Mã giảm giá</label>
                                <div class="input-group">
                                    <select class="form-control" name="voucher" id="voucher_id">
                                        @foreach ($vouchers as $voucher)
                                            <option value="{{ $voucher['id'] }}">{{ $voucher['title'] }}</option>
                                        @endforeach
                                    </select>


                                    <button class="btn btn-outline-primary">Áp dụng</button>
                                    <button class="btn btn-outline-secondary" type="button">Hủy</button>
                                </div>
                            </div>

                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between">
                                    <span>Voucher:</span>
                                    <span>0 đ</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Phí ship:</span>
                                    <input type="text" value="100" name="shipping_fee" id='shipping_fee'>
                                    {{-- <span>0 đ</span> --}}
                                </li>
                                <li class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Tổng tiền:</span>
                                    <span>{{ number_format($sub_total) }} đ</span>
                                </li>
                            </ul>

                            <button type="submit" class="btn btn-info w-100 text-white">Xác nhận đơn hàng</button>
                            <small class="d-block mt-2 text-center">
                                <a href="#">Tìm hiểu thêm thông tin về thuế và vận chuyển</a>
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- END: Checkout Page Section -->

@endsection
@section('script')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
