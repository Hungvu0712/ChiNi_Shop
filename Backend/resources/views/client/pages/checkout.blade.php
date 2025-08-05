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

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: transparent;
            font-weight: bold;
            font-size: 1.1rem;
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
                            <a href="/">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Thanh toán</span>
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

            <div class="card p-3 mb-3">
                <div class="card-header border-0 pb-2">
                    📦 Lựa chọn nơi nhận hàng ✓
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('addressDefault') }}">
                        @csrf
                        <!-- Danh sách địa chỉ -->
                        <div class="mb-3">
                            @foreach ($user->addresses as $address)
                                <div class="form-check border p-3 rounded mb-2">
                                    <input class="form-check-input" type="radio" name="address_id"
                                        value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}>
                                    <label class="form-check-label w-100">
                                        <strong>Nhà riêng</strong> <br>
                                        {{ $address->specific_address }} - {{ $address->address }} <br>
                                        <small>Người nhận: {{ $address->fullname }} - {{ $address->phone }}</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @if (!empty($user->addresses) && count($user->addresses) > 0)
                            <button type="submit" class="btn btn-primary">Xác nhận</button>
                        @endif
                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal"
                            data-bs-target="#addressModal">Thêm địa chỉ</button>
                    </form>
                    <!-- Modal thêm địa chỉ -->
                    <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('addAddressFromCheckout') }}" method="POST" class="modal-content p-3">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Địa chỉ mới</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <input type="text" class="form-control @error('fullname','addAddress') is-invalid @enderror"
                                            name="fullname" placeholder="Họ và tên" value="{{ old('fullname') }}">
                                        @error('fullname','addAddress')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control @error('phone','addAddress') is-invalid @enderror"
                                            name="phone" placeholder="Số điện thoại" value="{{ old('phone') }}">
                                        @error('phone','addAddress')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <select id="province" class="form-select">
                                                <option disabled selected>Tỉnh/Thành phố</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select id="district" class="form-select" disabled>
                                                <option disabled selected>Quận/Huyện</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select id="ward" class="form-select" disabled>
                                                <option disabled selected>Phường/Xã</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" id="fullAddress" name="address"
                                        class="form-control @error('address','addAddress') is-invalid @enderror" readonly
                                        value="{{ old('address') }}">
                                    @error('address','addAddress')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <div class="mb-2 mt-2">
                                        <input type="text"
                                            class="form-control @error('specific_address','addAddress') is-invalid @enderror"
                                            name="specific_address" placeholder="Địa chỉ cụ thể"
                                            value="{{ old('specific_address') }}">
                                        @error('specific_address','addAddress')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-check mb-3 mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_default"
                                            name="is_default">

                                        <label class="form-check-label" for="s_default">Đặt làm địa chỉ mặc định</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button class="btn btn-success" type="submit">Thêm mới</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Hiển thị lỗi riêng cho modal -->
                    {{-- @dd($errors->addAddress) --}}
                    @if ($errors->addAddress->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var modal = new bootstrap.Modal(document.getElementById('addressModal'));
                                modal.show();
                            });
                        </script>
                    @endif
                </div>
            </div>
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
                                <input type="text" class="form-control" name="user_name" id="user_name"
                                    value="{{ $addressDefault['fullname'] ?? '' }}">
                                @error('user_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="user_email" class="form-label">Email</label>
                                <input type="email" class="form-control" readonly name="user_email" id="user_email"
                                    value="{{ $user['email'] }}">
                            </div>

                            <div class="mb-3">
                                <label for="user_phonenumber" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="user_phonenumber" id="user_phonenumber"
                                    value="{{ $addressDefault['phone'] ?? '' }}">
                                @error('user_phonenumber')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="checkout-box">
                            <div class="section-title">📍 ĐỊA CHỈ GIAO HÀNG ✓</div>
                            <div class="mb-3">
                                <label for="ship_user_name" class="form-label">Tên người nhận</label>
                                <input type="text" class="form-control" name="ship_user_name" id="ship_user_name"
                                    value="{{ $addressDefault['fullname'] ?? '' }}">
                                @error('ship_user_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ship_user_phonenumber" class="form-label">Số điện thoại người nhận</label>
                                <input type="text" class="form-control" name="ship_user_phonenumber"
                                    id="ship_user_phonenumber" value="{{ $addressDefault['phone'] ?? '' }}">
                                @error('ship_user_phonenumber')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ship_user_address" class="form-label">Địa chỉ người nhận</label>
                                <textarea class="form-control" name="ship_user_address" id="ship_user_address" rows="2">{{ $address->specific_address ?? '' }}  {{ $address->address ?? '' }}</textarea>
                                @error('ship_user_address')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="user_note" class="form-label">Ghi chú</label>
                                <textarea class="form-control" name="user_note" id="user_note" rows="2" placeholder="Ghi chú">{{ $addressDefault['note'] ?? '' }}</textarea>
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
                                    <select class="form-select" name="voucher_code" id="voucher_id">
                                        <option disabled selected>Lựa chọn mã giảm giá</option>
                                        @foreach ($vouchers as $voucher)
                                            <option data-voucher-code="{{ $voucher['code'] }}"
                                                value="{{ $voucher['code'] }}">{{ $voucher['title'] }}</option>
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
    <script src="{{ asset('address/address.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const applyBtn = document.querySelector('.btn.btn-outline-primary');
            const cancelBtn = document.querySelector('.btn.btn-outline-secondary');
            const voucherSelect = document.getElementById('voucher_id');
            const subTotalEl = document.getElementById('sub_total');
            // const voucherDisplay = document.querySelector('ul li span:last-child');
            // console.log(voucherDisplay);
            let originalSubTotal = parseCurrency(subTotalEl.innerText); // Lưu tổng ban đầu

            applyBtn.addEventListener('click', async function(e) {
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
                    // voucherDisplay.innerText = `${formatCurrency(data.discount_amount)} đ`;
                    const newTotal = originalSubTotal - data.discount_amount;
                    subTotalEl.innerText = `${formatCurrency(newTotal)} đ`;
                    // console.log(voucherDisplay.innerText );
                    
                } catch (err) {
                    alert("Có lỗi xảy ra khi áp dụng voucher.");
                    console.error(err);
                }
            });

            cancelBtn.addEventListener('click', function() {
                voucherSelect.selectedIndex = 0;
                // voucherDisplay.innerText = `0 đ`;
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
