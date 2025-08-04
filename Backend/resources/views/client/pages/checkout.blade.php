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
        @php
            $errorStocks = session('errorStocks');
        @endphp
        
        @if (!empty($errorStocks))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errorStocks['out_of_stock'] ?? [] as $error)
                        <li>{{ $error['message'] ?? 'Lỗi không xác định' }}</li>
                    @endforeach

                    @foreach ($errorStocks['insufficient_stock'] ?? [] as $error)
                        <li>{{ $error['message'] ?? 'Lỗi không xác định' }}</li>
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
                                        <input class="form-check-input" type="radio"  name="payment_method_id"
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
                                    <select class="form-select"  name="voucher_code" id="voucher_id">
                                       <option disabled selected>Lựa chọn mã giảm giá</option>
                                        @foreach ($vouchers as $voucher)
                                            <option data-voucher-code="{{ $voucher['code'] }}" value="{{ $voucher['code'] }}">{{ $voucher['title'] }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary">Áp dụng</button>
                                    <button class="btn btn-outline-secondary" type="button">Hủy</button>
                                </div>
                            </div>

                            <ul class="list-unstyled">
                                {{-- <li class="d-flex justify-content-between">
                                    <span>Voucher:</span>
                                    <span>0 đ</span>
                                </li> --}}
                                <li class="d-flex justify-content-between">
                                    <span>Phí ship:</span>
                                    <span>30.000 đ</span>
                                    <input type="hidden" value="30000" name="shipping_fee" id='shipping_fee'>
                                </li>
                                <li class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Tổng tiền:</span>
                                    <span id="sub_total">{{ number_format($sub_total1) }} đ</span>
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
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const applyBtn = document.querySelector('.btn.btn-outline-primary');
    const cancelBtn = document.querySelector('.btn.btn-outline-secondary');
    const voucherSelect = document.getElementById('voucher_id');
    const subTotalEl = document.getElementById('sub_total');
    const voucherDisplay = document.querySelector('ul li span:last-child');

    let originalSubTotal = parseCurrency(subTotalEl.innerText); // Lưu tổng ban đầu

    applyBtn.addEventListener('click', async function (e) {
        e.preventDefault();

        const selectedOption = voucherSelect.options[voucherSelect.selectedIndex];
        const voucherCode = selectedOption.dataset.voucherCode;

        if (!voucherCode) {
            alert("Vui lòng chọn mã giảm giá.");
            return;
        }

        try {
            const response = await fetch("{{ route('apply-voucher') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    code: voucherCode,
                    order_total: originalSubTotal
                })
            });

            const data = await response.json();

            if (!response.ok) {
                alert(data.message || 'Đã xảy ra lỗi.');
                return;
            }

            // Nếu áp dụng thành công
            voucherDisplay.innerText = `${formatCurrency(data.discount_amount)} đ`;
            const newTotal = originalSubTotal - data.discount_amount;
            subTotalEl.innerText = `${formatCurrency(newTotal)} đ`;
        } catch (err) {
            alert("Có lỗi xảy ra khi áp dụng voucher.");
            console.error(err);
        }
    });

    cancelBtn.addEventListener('click', function () {
        voucherSelect.selectedIndex = 0;
        voucherDisplay.innerText = `0 đ`;
        subTotalEl.innerText = `${formatCurrency(originalSubTotal)} đ`;
    });

    // Helper: parse "100.000 đ" -> 100000
    function parseCurrency(str) {
        return parseInt(str.replace(/\D/g, '')) || 0;
    }

    // Helper: format 100000 -> "100.000"
    function formatCurrency(num) {
        return num.toLocaleString('vi-VN');
    }
});
</script>

@endsection
