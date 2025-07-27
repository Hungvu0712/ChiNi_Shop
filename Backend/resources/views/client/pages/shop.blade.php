@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Shop')
@section('css')
    <style>
        .color-picker {
            width: 20px;
            height: 20px;
            display: inline-block;
            border-radius: 50%;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .color-picker:hover {
            transform: scale(1.2);
            border-color: #333;
        }

        .color-picker.active {
            outline: 2px solid black;
            outline-offset: 1px;
        }

        .main-img,
        .hover-img {
            transition: opacity 0.3s ease;
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
                        <h2>Shop with Chini</h2>
                        <div class="pageBannerPath">
                            <a href="/">Home</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Shop</span>
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
                <div class="col-lg-4 col-xl-3">
                    <div class="shopSidebar">
                        <aside class="widget">
                            <h3 class="widgetTitle">Item Categories</h3>
                            <ul>
                                <li class="menu-item-has-children">
                                    <a href="javascript:void(0);">Accessories</a>
                                    <ul>
                                        <li><a href="shop_full_width.html">Bag</a></li>
                                        <li><a href="shop_left_sidebar.html">wallet</a></li>
                                        <li><a href="shop_right_sidebar.html">Hat</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="javascript:void(0);">Fashions</a>
                                    <ul>
                                        <li><a href="shop_full_width.html">Men</a></li>
                                        <li><a href="shop_left_sidebar.html">Women</a></li>
                                        <li><a href="shop_right_sidebar.html">Kids</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0);">Electronics</a></li>
                                <li class="menu-item-has-children">
                                    <a href="javascript:void(0);">Furniture</a>
                                    <ul>
                                        <li><a href="shop_full_width.html">Living</a></li>
                                        <li><a href="shop_left_sidebar.html">Kitchen</a></li>
                                        <li><a href="shop_right_sidebar.html">Office</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0);">Shoes</a></li>
                                <li class="menu-item-has-children">
                                    <a href="javascript:void(0);">Jewellary</a>
                                    <ul>
                                        <li><a href="shop_full_width.html">Gold</a></li>
                                        <li><a href="shop_left_sidebar.html">Diamond</a></li>
                                        <li><a href="shop_right_sidebar.html">Imitation</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="javascript:void(0);">Others</a>
                                    <ul>
                                        <li><a href="shop_full_width.html">Electronics</a></li>
                                        <li><a href="shop_left_sidebar.html">Phone</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </aside>
                        <aside class="widget priceFilter">
                            <h3 class="widgetTitle">Price Range</h3>
                            <div class="shopWidgetWraper">
                                <div class="priceFilterSlider">
                                    <form action="#" method="get" class="clearfix">
                                        <div id="sliderRange"></div>
                                        <div class="pfsWrap">
                                            <label>Price</label>
                                            <span id="amount"></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </aside>
                        <aside class="widget sizeFilter">
                            <h3 class="widgetTitle">Size</h3>
                            <div class="productSizeWrap">
                                <div class="pswItem">
                                    <input checked type="radio" name="ws_1" value="S" id="ws_1sdfsdf_s">
                                    <label for="ws_1sdfsdf_s">S</label>
                                </div>
                                <div class="pswItem">
                                    <input type="radio" name="ws_1" value="M" id="ws_1tst_m">
                                    <label for="ws_1tst_m">M</label>
                                </div>
                                <div class="pswItem">
                                    <input type="radio" name="ws_1" value="L" id="ws_1234_l">
                                    <label for="ws_1234_l">L</label>
                                </div>
                                <div class="pswItem">
                                    <input type="radio" name="ws_1" value="XL" id="ws_1_xl">
                                    <label for="ws_1_xl">XL</label>
                                </div>
                            </div>
                        </aside>
                        <aside class="widget colorFilter">
                            <h3 class="widgetTitle">Color</h3>
                            <div class="productColorWrap">
                                <div class="pcwItem">
                                    <input type="radio" checked name="wc_1" value="S" id="wc_1_1">
                                    <label for="wc_1_1"></label>
                                </div>
                                <div class="pcwItem pcwi2">
                                    <input type="radio" name="wc_1" value="M" id="wc_1_2">
                                    <label for="wc_1_2"></label>
                                </div>
                                <div class="pcwItem pcwi3">
                                    <input type="radio" name="wc_1" value="L" id="wc_1_3">
                                    <label for="wc_1_3"></label>
                                </div>
                                <div class="pcwItem pcwi4">
                                    <input type="radio" name="wc_1" value="XL" id="wc_1_4">
                                    <label for="wc_1_4"></label>
                                </div>
                                <div class="pcwItem pcwi5">
                                    <input type="radio" name="wc_1" value="XL" id="wc_1_5">
                                    <label for="wc_1_5"></label>
                                </div>
                            </div>
                        </aside>
                        <aside class="widget">
                            <h3 class="widgetTitle">Brand Name</h3>
                            <ul>
                                <li><a href="javascript:void(0);">Sony</a></li>
                                <li><a href="javascript:void(0);">Lenovo</a></li>
                                <li><a href="javascript:void(0);">Jonson & Handson</a></li>
                                <li><a href="javascript:void(0);">Apple</a></li>
                                <li><a href="javascript:void(0);">Google</a></li>
                                <li><a href="javascript:void(0);">Hp</a></li>
                                <li><a href="javascript:void(0);">Uniliver</a></li>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="row shopProductRow">
                        <div class="col-lg-12">
                            <div class="tab-content productViewTabContent" id="productViewTabContent">
                                <div class="tab-pane show active" id="grid-tab-pane" role="tabpanel"
                                    aria-labelledby="grid-tab" tabindex="0">
                                    <div class="row">
                                        @foreach ($products as $product)
                                            <div class="col-sm-6 col-xl-4">
                                                <div class="productItem01" data-product-id="{{ $product->id }}">
                                                    <div
                                                        class="pi01Thumb ratio ratio-1x1 position-relative overflow-hidden">
                                                        <img class="main-img img-fluid w-100 h-100 object-fit-cover position-absolute top-0 start-0"
                                                            src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                                            alt="{{ $product->name }}">
                                                        <img class="hover-img img-fluid w-100 h-100 object-fit-cover position-absolute top-0 start-0"
                                                            src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                                            alt="{{ $product->name }}">
                                                    </div>


                                                    <div class="pi01Details">
                                                        <h3 class="product-name h5 mb-2"><a
                                                                href="{{ route('client.shop.show', $product->slug) }}">{{ $product->name }}</a>
                                                        </h3>



                                                        <div class="d-flex flex-column mt-2 gap-2">
                                                            <div
                                                                class="variant-row d-flex flex-wrap justify-content-between align-items-center mb-3">
                                                                {{-- Màu sắc --}}
                                                                @if (!empty($product->colorData))
                                                                    <div
                                                                        class="color-options d-flex flex-wrap gap-2 align-items-center mb-2 mb-sm-0">
                                                                        @foreach ($product->colorData as $color)
                                                                            <span
                                                                                class="color-picker rounded-circle border border-light shadow-sm"
                                                                                style="width: 24px; height: 24px; cursor: pointer; background-color: {{ $color['hex'] }};"
                                                                                data-image="{{ asset($color['image']) }}"
                                                                                data-name="{{ $color['variant_name'] }}"
                                                                                data-price="{{ number_format($color['price']) }} VNĐ"
                                                                                title="{{ ucfirst($color['name']) }}"
                                                                                data-bs-toggle="tooltip">
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                {{-- Các thuộc tính khác --}}
                                                                <div class="attribute-options d-flex flex-wrap gap-2">
                                                                    @foreach ($product->attributesGroup as $name => $values)
                                                                        @if ($name != 'Màu sắc')
                                                                            @foreach ($values as $value)
                                                                                <span
                                                                                    class="attribute-item badge bg-light text-dark border border-1">
                                                                                    {{ $value }}
                                                                                </span>
                                                                            @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row g-2">
                                                            <div class="col-sm-6">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-center h-100 p-2 border rounded bg-light">
                                                                    <span class="fw-bold fs-5 text-danger">
                                                                        {{ number_format($product->price ?? 0) }} VNĐ
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <a href="{{ route('client.shop.show', $product->slug) }}"
                                                                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                                                                    <i class="fas fa-eye me-2"></i>
                                                                    Chi tiết
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="mt-4 d-flex justify-content-center">
                                            {{ $products->links() }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
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
            picker.addEventListener('click', function() {
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
            sizeInput.addEventListener('change', function() {
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
@endsection
