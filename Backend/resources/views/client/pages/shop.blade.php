@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Shop')
@section('css')
    <style>
         .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .product-img-container {
        position: relative;
        overflow: hidden;
        padding-top: 100%; /* Tỉ lệ 1:1 */
    }

    .product-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.3s ease;
    }

    .product-img.hover-img {
        opacity: 0;
    }

    .product-card:hover .main-img {
        opacity: 0;
    }

    .product-card:hover .hover-img {
        opacity: 1;
    }

    /* Nút action */
    .product-actions a {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: white;
        color: #333;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .product-actions a:hover {
        background: #7b9691;
        color: white;
        transform: scale(1.1);
    }

    /* Màu sắc */
    .color-option {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.2s ease;
    }

    .color-option:hover {
        transform: scale(1.2);
    }

    .color-option.active {
        border-color: #000;
    }

    /* Thuộc tính sản phẩm */
    .attribute-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
    }

    .attribute-badge:hover {
        background: #000 !important;
        color: white !important;
    }

    /* Section tiêu đề */
    .section-title {
        position: relative;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: #7b9691;
    }

    /* Feature boxes */
    .feature-box {
        padding: 2rem 1.5rem;
        border-radius: 0.5rem;
        height: 100%;
        transition: transform 0.3s ease;
        background: #f8f9fa;
    }

    .feature-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .feature-box i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #7b9691;
    }
    </style>
@endsection
@section('content')
    <!-- BEGIN: Page Banner Section -->
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Sản phẩm nhà Chini</h2>
                        <div class="pageBannerPath">
                            <a href="/">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Sản phẩm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Page Banner Section -->

    <!-- BEGIN: Shop Page Section -->
    <section class="shopPageSection shopPageHasSidebar">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-4 col-xl-3">
                    <div class="shopSidebar">
                        {{-- Item Categories --}}
                        <aside class="widget">
                            <h3 class="widgetTitle">Danh mục</h3>
                            <ul>
                                @foreach ($categories as $category)
                                    <li>
                                        <label>
                                            <input type="radio" name="category_id" class="filter-option"
                                                value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </aside>

                        {{-- Price Range --}}
                        <aside class="widget priceFilter">
                            <h3 class="widgetTitle">Giá</h3>
                            <div class="shopWidgetWraper">
                                <div id="sliderRange"></div>
                                <div class="pfsWrap mt-2">
                                    <label>Giá:</label>
                                    <span id="amount" class="fw-bold text-danger"></span>
                                    <input type="hidden" id="min_price" class="filter-option"
                                        value="{{ request('min_price', 0) }}">
                                    <input type="hidden" id="max_price" class="filter-option"
                                        value="{{ request('max_price', 100000000) }}">
                                </div>
                            </div>
                        </aside>

                        {{-- Dynamic Attributes --}}
                        @foreach ($attributes as $attribute)
                            <aside class="widget">
                                <h3 class="widgetTitle">{{ $attribute->name }}</h3>
                                <div>
                                    @foreach ($attribute->attributeValues as $value)
                                        <div class="filterItem">
                                            <label>
                                                <input type="checkbox" name="attributes[{{ $attribute->id }}][]"
                                                    class="filter-option" value="{{ $value->id }}">
                                                {{ $value->value }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </aside>
                        @endforeach

                        {{-- Brand Name --}}
                        <aside class="widget">
                            <h3 class="widgetTitle">Brand Name</h3>
                            <ul>
                                @foreach ($brands as $brand)
                                    <li>
                                        <label>
                                            <input type="radio" name="brand_id" class="filter-option" value="{{ $brand->id }}">
                                            {{ $brand->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </aside>
                    </div>
                </div>

                <!-- Product List -->
                <div class="col-lg-8 col-xl-9">
                    <div id="product-list">
                        @include('client.pages.product_list', ['products' => $products])
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- END: Shop Page Section -->
@endsection
@section('script')
    <script>
        const selectedState = {};

        // === Chọn MÀU ===
        document.querySelectorAll('.color-picker').forEach(picker => {
            picker.addEventListener('click', function () {
                const productId = this.dataset.productId;
                const color = this.dataset.color;
                const map = JSON.parse(this.dataset.map || '{}');

                selectedState[productId] = selectedState[productId] || {};
                selectedState[productId].color = color;
                selectedState[productId].variantMap = map;

                console.log('------------------------');
                console.log('✅ COLOR CLICKED:', color);
                console.log('✅ VARIANT MAP:', map);
                console.log('✅ STATE AFTER COLOR:', selectedState[productId]);
            });
        });

        // === Chọn SIZE ===
        document.querySelectorAll('.size-picker').forEach(sizeInput => {
            sizeInput.addEventListener('change', function () {
                const productId = this.dataset.productId;
                const size = this.value;

                selectedState[productId] = selectedState[productId] || {};
                selectedState[productId].size = size;

                console.log('------------------------');
                console.log('✅ SIZE PICKED:', size);
                console.log('✅ STATE AFTER SIZE:', selectedState[productId]);

                tryUpdate(productId);
            });
        });

        function tryUpdate(productId) {
            const state = selectedState[productId];
            if (!state || !state.color || !state.size) {
                console.warn('⛔ Missing COLOR or SIZE!');
                return;
            }

            const key = `${state.color}-${state.size}`;
            const variant = state.variantMap[key];

            console.log('------------------------');
            console.log('🔑 KEY TO LOOKUP:', key);
            console.log('📂 FULL VARIANT MAP:', state.variantMap);
            console.log('🧩 VARIANT RESULT:', variant);

            if (!variant) {
                alert('⛔ Cặp này chưa có trong CMS!');
                return;
            }

            // === Tìm đúng block: Có thể là productItem01 (grid) hoặc productItem02 (list)
            const block = document.querySelector(
                `.productItem01[data-product-id="${productId}"], .productItem02[data-product-id="${productId}"]`);
            if (!block) {
                console.error('⛔ Block not found for productId:', productId);
                return;
            }

            const mainImg = block.querySelector('.main-img');
            const hoverImg = block.querySelector('.hover-img');
            const priceEl = block.querySelector('.product-price');

            console.log('🖼️ MAIN IMG ELEMENT:', mainImg);
            console.log('🖼️ HOVER IMG ELEMENT:', hoverImg);
            console.log('💵 PRICE ELEMENT:', priceEl);
            console.log('📸 VARIANT IMAGE:', variant.image);
            console.log('💰 VARIANT PRICE:', variant.price);

            if (mainImg && variant.image) {
                mainImg.src = variant.image + '?v=' + Date.now();
                console.log('✅ MAIN IMG UPDATED!');
            }
            if (hoverImg && variant.image) {
                hoverImg.src = variant.image + '?v=' + Date.now();
                console.log('✅ HOVER IMG UPDATED!');
            }
            if (priceEl && variant.price) {
                priceEl.textContent = Number(variant.price).toLocaleString() + ' VNĐ';
                console.log('✅ PRICE UPDATED!');
            }

            console.log('🎉 DONE UPDATE for', key);
        }
    </script>
    <script>
        $(function () {
            // --- CÀI ĐẶT THANH TRƯỢT GIÁ ---
            $("#sliderRange").slider({
                range: true,
                min: {{ $priceMin ?? 0 }},
                max: {{ $priceMax ?? 100000000 }},
                step: 50000,
                values: [$("#min_price").val(), $("#max_price").val()],
                slide: function (event, ui) {
                    $("#amount").text(
                        ui.values[0].toLocaleString('vi-VN') + " VND - " +
                        ui.values[1].toLocaleString('vi-VN') + " VND"
                    );
                },
                stop: function (event, ui) {
                    // Chỉ cập nhật giá trị và lọc khi người dùng đã kéo xong
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                    fetchProducts();
                }
            });

            // Hiển thị giá ban đầu
            $("#amount").text(
                $("#sliderRange").slider("values", 0).toLocaleString('vi-VN') + " VND - " +
                $("#sliderRange").slider("values", 1).toLocaleString('vi-VN') + " VND"
            );

            // --- HÀM LỌC SẢN PHẨM CHÍNH ---
            function fetchProducts() {
                // Gom nhóm các thuộc tính được chọn
                let attributes = {};
                $('input[name^="attributes"]:checked').each(function () {
                    // Trích xuất ID thuộc tính từ name="attributes[3][]"
                    let attributeId = $(this).attr('name').match(/\[(\d+)\]/)[1];

                    // Khởi tạo mảng nếu chưa có
                    if (!attributes[attributeId]) {
                        attributes[attributeId] = [];
                    }

                    // Thêm giá trị ID vào mảng
                    attributes[attributeId].push($(this).val());
                });

                // Gửi request AJAX
                $.ajax({
                    url: "{{ route('client.shop.filter') }}",
                    method: "GET",
                    data: {
                        min_price: $("#min_price").val(),
                        max_price: $("#max_price").val(),
                        category_id: $('input[name="category_id"]:checked').val() || '',
                        brand_id: $('input[name="brand_id"]:checked').val() || '',
                        attributes: attributes // Gửi dưới dạng object
                    },
                    beforeSend: function () {
                        // Tùy chọn: Thêm hiệu ứng loading ở đây
                        $("#product-list").addClass('loading');
                    },
                    success: function (res) {
                        $("#product-list").html(res);
                    },
                    error: function (xhr) {
                        console.error('Lỗi khi lọc sản phẩm:', xhr.responseText);
                        alert('Đã có lỗi xảy ra, vui lòng thử lại.');
                    },
                    complete: function () {
                        // Tùy chọn: Bỏ hiệu ứng loading
                        $("#product-list").removeClass('loading');
                    }
                });
            }

            // --- GỌI HÀM LỌC KHI CÓ THAY ĐỔI ---
            // Bắt sự kiện thay đổi của tất cả các lựa chọn filter
            $(document).on("change", ".filter-option", fetchProducts);

            // Xử lý cho phép bỏ chọn radio button
            $(document).on('click', 'input[type="radio"].filter-option', function () {
                const name = $(this).attr('name');
                if ($(this).data('waschecked') == true) {
                    $(this).prop('checked', false);
                    $(this).data('waschecked', false);
                    fetchProducts(); // Lọc lại khi bỏ chọn
                } else {
                    $(this).data('waschecked', true);
                    // Sự kiện "change" đã được gọi ở trên nên không cần gọi fetchProducts() ở đây
                }
            });
        });
    </script>
@endsection
