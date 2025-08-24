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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-img-container {
            position: relative;
            overflow: hidden;
            padding-top: 100%;
            /* Tỉ lệ 1:1 */
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
                        {{-- Search Bar --}}
                        {{-- Search Bar --}}
                        <aside class="widget">
                            <h3 class="widgetTitle">Tìm kiếm</h3>
                            <div class="shopWidgetWraper">
                                <input type="text" id="search_keyword" class="form-control"
                                    placeholder="Nhập tên sản phẩm...">
                            </div>
                        </aside>


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

                console.log('✅ COLOR CLICKED:', color, selectedState[productId]);
            });
        });

        // === Chọn SIZE ===
        document.querySelectorAll('.size-picker').forEach(sizeInput => {
            sizeInput.addEventListener('change', function () {
                const productId = this.dataset.productId;
                const size = this.value;

                selectedState[productId] = selectedState[productId] || {};
                selectedState[productId].size = size;

                console.log('✅ SIZE PICKED:', size, selectedState[productId]);
                tryUpdate(productId);
            });
        });

        function tryUpdate(productId) {
            const state = selectedState[productId];
            if (!state || !state.color || !state.size) return;

            const key = `${state.color}-${state.size}`;
            const variant = state.variantMap[key];
            if (!variant) return;

            const block = document.querySelector(
                `.productItem01[data-product-id="${productId}"], .productItem02[data-product-id="${productId}"]`
            );
            if (!block) return;

            const mainImg = block.querySelector('.main-img');
            const hoverImg = block.querySelector('.hover-img');
            const priceEl = block.querySelector('.product-price');

            if (mainImg && variant.image) mainImg.src = variant.image + '?v=' + Date.now();
            if (hoverImg && variant.image) hoverImg.src = variant.image + '?v=' + Date.now();
            if (priceEl && variant.price) priceEl.textContent = Number(variant.price).toLocaleString() + ' VNĐ';
        }
    </script>

    <script>
        $(function () {
            // --- Thanh trượt giá ---
            $("#sliderRange").slider({
                range: true,
                min: {{ $priceMin ?? 0 }},
                max: {{ $priceMax ?? 100000000 }},
                step: 10000,
                values: [$("#min_price").val(), $("#max_price").val()],
                slide: function (event, ui) {
                    $("#amount").text(
                        ui.values[0].toLocaleString('vi-VN') + " VND - " +
                        ui.values[1].toLocaleString('vi-VN') + " VND"
                    );
                },
                stop: function (event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                    fetchProducts();
                }
            });

            $("#amount").text(
                $("#sliderRange").slider("values", 0).toLocaleString('vi-VN') + " VND - " +
                $("#sliderRange").slider("values", 1).toLocaleString('vi-VN') + " VND"
            );

            // --- Hàm lấy dữ liệu filter ---
            function getFilterData(extra = {}) {
                let attributes = {};
                $('input[name^="attributes"]:checked').each(function () {
                    let attributeId = $(this).attr('name').match(/\[(\d+)\]/)[1];
                    if (!attributes[attributeId]) attributes[attributeId] = [];
                    attributes[attributeId].push($(this).val());
                });

                return {
                    min_price: $("#min_price").val(),
                    max_price: $("#max_price").val(),
                    category_id: $('input[name="category_id"]:checked').val() || '',
                    brand_id: $('input[name="brand_id"]:checked').val() || '',
                    attributes: attributes,
                    search: $("#search_keyword").val() || '',
                    ...extra
                };
            }

            // --- Gọi Ajax filter ---
            function fetchProducts(extra = {}) {
                $.ajax({
                    url: "{{ route('client.shop.filter') }}",
                    method: "GET",
                    data: getFilterData(extra),
                    beforeSend: function () {
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
                        $("#product-list").removeClass('loading');
                    }
                });
            }

            // --- Trigger khi thay đổi ---
            $(document).on("change", ".filter-option", function () {
                fetchProducts();
            });

            // --- Xử lý radio bỏ chọn ---
            $(document).on('click', 'input[type="radio"].filter-option', function () {
                const name = $(this).attr('name');
                if ($(this).data('waschecked') == true) {
                    $(this).prop('checked', false);
                    $(this).data('waschecked', false);
                    fetchProducts();
                } else {
                    $('input[name="' + name + '"]').data('waschecked', false);
                    $(this).data('waschecked', true);
                }
            });

            // --- Search debounce ---
            let typingTimer;
            $(document).on("keyup", "#search_keyword", function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(fetchProducts, 500);
            });
            $(document).on("keypress", "#search_keyword", function (e) {
                if (e.which === 13) {
                    fetchProducts();
                }
            });

            // --- Pagination Ajax ---
            $(document).on("click", "#product-list .pagination a", function (e) {
                e.preventDefault();
                let url = new URL($(this).attr("href"), window.location.origin);
                let page = url.searchParams.get("page") || 1;
                fetchProducts({ page: page });
            });
        });
    </script>
@endsection