@extends('client.layouts.master')
@section('title', 'Cart')
@section('content')
    <!-- BEGIN: Search Popup Section -->
    <section class="popup_search_sec">
        <div class="popup_search_overlay"></div>
        <div class="pop_search_background">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="popup_logo">
                            <a href="index.html"><img src="images/logo2.png" alt="Ulina"></a>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <a href="javascript:void(0);" id="search_Closer" class="search_Closer"></a>
                    </div>
                </div>
            </div>
            <div class="middle_search">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <div class="popup_search_form">
                                <form method="get" action="#">
                                    <input type="search" name="s" id="s" placeholder="Type Words and Hit Enter">
                                    <button type="submit"><i class="fa-solid fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Search Popup Section -->

    <!-- BEGIN: Page Banner Section -->
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Shopping Cart</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('home.index')}}">Home</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Page Banner Section -->
    @if (isset($message))
        <div class="text-center py-5">
            <h1>{{ $message }}</h1>
        </div>
    @else
        <!-- END: Cart Page Section -->
        <section class="cartPageSection woocommerce">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cartHeader">
                            <h3>Giỏ hàng của bạn</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <form id="deleteForm" action="{{ route('cart.destroy', '__ID__') }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="cart_item_ids_json" id="cart_item_ids_json" value="">

                            <table class="shop_table cart_table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th class="product-thumbnail">Tên sản phẩm</th>
                                        <th class="product-name">&nbsp;</th>
                                        <th>Thuộc tính</th>
                                        <th class="product-price">Giá</th>
                                        <th class="product-quantity">Số lượng</th>
                                        <th class="product-subtotal">Tổng tiền</th>
                                        <th class="product-remove">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart['cartitems'] as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="item-checkbox" value="{{ $item['id'] }}">
                                            </td>
                                            <td class="product-thumbnail">
                                                <img src="{{ $item['productvariant']['variant_image'] }}" style="width: 100px; height: 100px;">
                                            </td>
                                            <td class="product-name">
                                                {{ $item['product']['name'] }}
                                            </td>
                                            <td>
                                                @foreach ($item['productvariant']['attributes'] as $attribute)
                                                <div class="attribute-group">
                                                    <strong>{{ $attribute['name'] }}:</strong>
                                                    @php
            $value = mb_strtolower($attribute['pivot']['value']);
                                                    @endphp

                                                    @if (mb_strtolower($attribute['name']) === 'color' || mb_strtolower($attribute['name']) === 'màu')
                                                    <span class="color-dot"
                                                        style="display:inline-block;width:15px;height:15px;border-radius:50%;background-color:{{ $colorMap[$value] ?? '#ccc' }};border:1px solid #000;">
                                                    </span>
                                                    <span>{{ $attribute['pivot']['value'] }}</span>
                                                    @else
                                                    <span>{{ $attribute['pivot']['value'] }}</span>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </td>
                                            <td>{{ number_format($item['productvariant']['price']) }} VND</td>
                                            <td class="product-quantity">
                                            <div class="quantity clearfix">
                                                {{-- @dd($item["productvariant"]["attributes"]->toArray()) --}}
                                               <button type="button" class="qtyBtn btnMinus"  data-cart-id="{{ $item['id'] }}">-</button>

                                            <input type="number" readonly class="carqty input-text qty text" name="quantity" value="{{ $item['quantity'] }}"
                                                data-cart-id="{{ $item['id'] }}" id="qty_{{ $item['id'] }}"
                                                data-price="{{ $item['productvariant']['price'] }}"
                                                data-variant='@json(collect($item["productvariant"]["attributes"])->pluck("pivot.attribute_value_id", "name"))'>

                                            <button type="button" class="qtyBtn btnPlus" data-cart-id="{{ $item['id'] }}">+</button>

                                            </div>
                                            </td>
                                            <td id="total_{{ $item['id'] }}">{{ number_format($item['quantity'] * $item['productvariant']['price']) }} VND</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" class="text-end">
                                            <button type="button" id="deleteSelectedBtn" class="ulinaBTN2">Xoá đã chọn</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>

                    </div>
                </div>
                <div class="row cartAccessRow">
                    <div class="col-8">
                        <h3>Số tiền tạm tính</h3>
                    </div>
                    <div class="col-lg-4">
                        <div class="col-sm-12 cart_totals">
                            <table class="shop_table shop_table_responsive">
                                <tr class="order-total">
                                    <th>Thành tiền</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price">
                                            <ins id="subtotal_display">{{ number_format($sub_total)}} VND</ins>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <a class="checkout-button ulinaBTN" id="checkoutBtn">
                                <span>Thanh toán</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Cart Page Section -->
    @endif

@endsection
@section('script')
    {{-- xóa cartitem --}}
               <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const checkAllBox = document.getElementById('checkAll');
                    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
                    const deleteBtn = document.getElementById('deleteSelectedBtn');
                    const deleteForm = document.getElementById('deleteForm');
                    const hiddenInput = document.getElementById('cart_item_ids_json');

                    if (!checkAllBox || !deleteBtn || !deleteForm || !hiddenInput) return;

                    // Khi click checkAll
                    checkAllBox.addEventListener('change', function () {
                        itemCheckboxes.forEach(cb => cb.checked = this.checked);
                    });

                    // Khi click từng checkbox
                    itemCheckboxes.forEach(cb => {
                        cb.addEventListener('change', function () {
                            const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
                            const noneChecked = Array.from(itemCheckboxes).every(c => !c.checked);

                            if (allChecked) {
                                checkAllBox.checked = true;
                                checkAllBox.indeterminate = false;
                            } else if (noneChecked) {
                                checkAllBox.checked = false;
                                checkAllBox.indeterminate = false;
                            } else {
                                checkAllBox.checked = false;
                                checkAllBox.indeterminate = true;
                            }
                        });
                    });

                    // Xoá đã chọn
                    deleteBtn.addEventListener('click', function () {
                        const checked = Array.from(itemCheckboxes).filter(cb => cb.checked);
                        if (checked.length === 0) {
                            alert('Vui lòng chọn ít nhất một sản phẩm để xoá.');
                            return;
                        }

                        const ids = checked.map(cb => cb.value);

                        const jsonIds = JSON.stringify(ids);

                        deleteForm.action = deleteForm.action.replace('__ID__', encodeURIComponent(jsonIds));
                        hiddenInput.value = jsonIds;

                        if (confirm('Bạn có chắc chắn muốn xoá các sản phẩm đã chọn không?')) {
                            deleteForm.submit();
                        }
                    });
                });
            </script>
            {{-- Cập nhật sản phẩm --}}
           <script>
        document.querySelectorAll('.btnPlus, .btnMinus').forEach(btn => {
            btn.addEventListener('click', async function () {
                const cartId = this.dataset.cartId;
                const input = document.querySelector('#qty_' + cartId);

                if (!input) {
                    console.error("Không tìm thấy input với ID: qty_" + cartId);
                    return;
                }

                let currentQty = parseInt(input.value);
                let newQty = currentQty;

                if (this.classList.contains('btnPlus')) {
                    newQty++;
                } else if (this.classList.contains('btnMinus')) {
                   if (currentQty > 1) {
                        newQty--;
                    } else {
                        alert("⚠ Số lượng tối thiểu là 1");
                        return;
                    }
                }

                // Lấy product_variant từ data-variant
                let variantData = input.dataset.variant;
                let product_variant = {};
                try {
                    product_variant = variantData ? JSON.parse(variantData) : {};
                } catch (e) {
                    console.error("Lỗi khi parse JSON variant:", e);
                }

                try {
                    const res = await fetch(`/cart/${cartId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity: newQty,
                            product_variant: product_variant
                        })
                    });

                    if (!res.ok) {
                        const error = await res.json();
                        alert("❌ " + (error.message || 'Có lỗi xảy ra'));
                        return; // ❌ không cập nhật giao diện nếu lỗi
                    }

                    const data = await res.json();
                    alert("✅ " + data.message);

                    // ✅ Nếu OK thì cập nhật giao diện
                    input.value = newQty;

                    // Cập nhật tổng tiền sản phẩm (nếu bạn có thẻ hiển thị riêng)
                    const pricePerItem = parseFloat(input.dataset.price); // bạn cần gắn data-price vào input
                    const totalCell = document.querySelector('#total_' + cartId);


                    if (totalCell && pricePerItem) {
                        totalCell.innerText = new Intl.NumberFormat('vi-VN').format(newQty * pricePerItem) + ' VND';
                        updateGrandTotal();
                    }

                } catch (err) {
                    console.error("Lỗi khi gọi API:", err);
                    alert("❌ Không thể cập nhật. Vui lòng thử lại sau.");
                }
            });
        });
        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll('[id^="total_"]').forEach(el => {
                let text = el.innerText.replace(/[^\d]/g, ''); // loại bỏ ' VND' và dấu phẩy
                if (text) {
                    total += parseFloat(text);
                }
            });

            // Gán lại vào ô subtotal
            const grandTotalEl = document.querySelector('.order-total ins');
            if (grandTotalEl) {
                grandTotalEl.innerText = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
            }
        }

        </script>

        {{-- Trang thanh toán --}}
      <script>
    document.addEventListener('DOMContentLoaded', function () {
    const checkoutBtn = document.getElementById('checkoutBtn');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    if (!checkoutBtn) return;

    checkoutBtn.addEventListener('click', function () {
        const checked = Array.from(itemCheckboxes).filter(cb => cb.checked);

        if (checked.length === 0) {
            toastr.warning('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
            return;
        }

        const ids = checked.map(cb => parseInt(cb.value));
        const payload = {
            cart_item_ids: ids
        };

        fetch('{{ route("checkout.validateCartToCheckOut") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            const data = await response.json();

            if (!response.ok) {
                // ❌ Xử lý lỗi từ server
                if (data.errors) {
                    if (data.errors.out_of_stock) {
                        data.errors.out_of_stock.forEach(item => {
                            toastr.error(item.message || 'Sản phẩm đã hết hàng.');

                            // Xoá dòng sản phẩm khỏi giao diện
                            const checkbox = document.querySelector(`.item-checkbox[value="${item.cart_id}"]`);
                            if (checkbox) {
                                const row = checkbox.closest('tr');
                                if (row) row.remove();
                            }
                        });
                    }

                    if (data.errors.insufficient_stock) {
                        data.errors.insufficient_stock.forEach(item => {
                            toastr.warning(item.message || 'Số lượng sản phẩm không đủ.');
                            
                            // Cập nhật lại số lượng
                            const qtyInput = document.getElementById(`qty_${item.cart_id}`);
                            if (qtyInput) qtyInput.value = item.max_quantity;

                            // Cập nhật lại tổng tiền theo số lượng mới (nếu có)
                            const price = parseFloat(qtyInput.getAttribute('data-price'));
                            const total = price * qtyInput.value;
                            const totalTd = document.getElementById(`total_${item.cart_id}`);
                            if (totalTd) {
                                totalTd.textContent = total.toLocaleString() + ' VND'
                            };
                            const subtotalDisplay = document.getElementById('subtotal_display');
                            console.log(subtotalDisplay,item.sub_total);
                            
                            if (subtotalDisplay) {
                                subtotalDisplay.textContent = data.sub_total.toLocaleString() + ' VND';
                            }
                        });
                    }
                }
                updateCartCount(data.total_items);
                throw new Error(data.message || 'Xảy ra lỗi trong quá trình thanh toán');
            }

            // Lưu data đơn hàng vào sessionStorage để load ở trang checkout
            // sessionStorage.setItem('checkout_data', JSON.stringify(data));

            // Chuyển trang đến checkout
            window.location.href = "{{ route('checkout.show') }}" + `?cart_item_ids=${data.cart_item_ids}`;

        })
        .catch(error => {
            console.error('❌ Lỗi khi thanh toán:', error.message);
        });
    });
});
</script>

@endsection