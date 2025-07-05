@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Shop')
@section('css')
    <style>
        .color-picker.active {
            outline: 2px solid #000;
            outline-offset: 2px;
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
                        <aside class="widget">
                            <h3 class="widgetTitle">Featured Items</h3>
                            <div class="productWidgets">
                                <div class="pwItems">
                                    <img src="images/widgets/1.jpg" alt="Ulina Product" />
                                    <h3><a href="shop_details1.html">Luxurius trendy dress for women</a></h3>
                                    <div class="pi01Price">
                                        <ins>$99</ins>
                                    </div>
                                </div>
                                <div class="pwItems">
                                    <img src="images/widgets/2.jpg" alt="Ulina Product" />
                                    <h3><a href="shop_details2.html">Ladies complete blazer suit</a></h3>
                                    <div class="pi01Price">
                                        <ins>$920</ins>
                                    </div>
                                </div>
                                <div class="pwItems">
                                    <img src="images/widgets/3.jpg" alt="Ulina Product" />
                                    <h3><a href="shop_details1.html">Full sleeve cotton <br />t-shirt</a></h3>
                                    <div class="pi01Price">
                                        <ins>$49</ins>
                                        <del>$58</del>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="row shopAccessRow">
                        <div class="col-sm-6">
                            <div class="productCount">Showing <strong>1 - 16</strong> of <strong>220</strong> items</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="shopAccessBar">
                                <div class="sortNav">
                                    <form method="post" action="#">
                                        <label>Sort By</label>
                                        <select name="productFilter">
                                            <option value="">Default</option>
                                            <option value="1">High to low</option>
                                            <option value="2">Low to high</option>
                                            <option value="3">Top rated</option>
                                            <option value="4">Recently viewed</option>
                                        </select>
                                    </form>
                                </div>
                                <ul class="nav productViewTabnav" id="productViewTab" role="tablist">
                                    <li role="presentation">
                                        <button id="list-tab" data-bs-toggle="tab" data-bs-target="#list-tab-pane"
                                            type="button" role="tab" data-aria-controls="list-tab-pane"
                                            data-aria-selected="false" aria-selected="false" tabindex="-1"><i
                                                class="fa-solid fa-list"></i></button>
                                    </li>
                                    <li role="presentation">
                                        <button class="active" id="grid-tab" data-bs-toggle="tab"
                                            data-bs-target="#grid-tab-pane" type="button" role="tab"
                                            data-aria-controls="grid-tab-pane" data-aria-selected="true"
                                            aria-selected="true"><i class="fa-solid fa-table-cells"></i></button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row shopProductRow">
                        <div class="col-lg-12">
                            <div class="tab-content productViewTabContent" id="productViewTabContent">
                                <div class="tab-pane show active" id="grid-tab-pane" role="tabpanel"
                                    aria-labelledby="grid-tab" tabindex="0">
                                    <div class="row">
                                        @foreach ($products as $product)
                                            <div class="col-sm-6 col-xl-4">
                                                <div class="productItem01" data-product-id="{{ $product->id }}">
                                                    <div class="pi01Thumb">
                                                        <img class="main-img"
                                                            src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                                            alt="{{ $product->name }}">
                                                        <img class="hover-img"
                                                            src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                                            alt="{{ $product->name }}">
                                                    </div>

                                                    <div class="pi01Details">
                                                        <h3 class="product-name">
                                                            <a href="{{ route('client.shop.show', $product->slug) }}">
                                                                {{ $product->name }}
                                                            </a>
                                                        </h3>

                                                        <div class="pi01Price">
                                                            <ins class="product-price">
                                                                {{ number_format($product->price) }} VNĐ
                                                            </ins>
                                                        </div>

                                                        <div class="pi01Variations">

                                                            {{-- Màu sắc --}}
                                                            <div class="pi01VColor d-flex gap-1">
                                                                @php
                                                                    $colorMap = [
                                                                        'do' => '#e74c3c',
                                                                        'xanh' => '#3498db',
                                                                        'trang' => '#ffffff',
                                                                        'den' => '#2c3e50',
                                                                        'vang' => '#f1c40f',
                                                                        'black' => '#2c3e50',
                                                                        'white' => '#ffffff',
                                                                    ];
                                                                @endphp

                                                                @foreach ($product->colors as $color)
                                                                    @php
                                                                        $slugColor = strtolower(Str::slug($color));
                                                                        $hex = $colorMap[$slugColor] ?? '#ccc';
                                                                        $border = $hex === '#ffffff' ? '#999' : '#ccc';
                                                                        $boxShadow =
                                                                            $hex === '#ffffff'
                                                                                ? 'box-shadow: 0 0 2px #999;'
                                                                                : '';

                                                                        // Tách variantMap chỉ cho MÀU này
                                                                        $variantsForColor = [];
                                                                        foreach (
                                                                            $product->variantMap
                                                                            as $key => $variant
                                                                        ) {
                                                                            if (
                                                                                Str::startsWith($key, $slugColor . '-')
                                                                            ) {
                                                                                $variantsForColor[$key] = $variant;
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    <span class="color-picker"
                                                                        data-product-id="{{ $product->id }}"
                                                                        data-color="{{ $slugColor }}"
                                                                        data-map='@json($variantsForColor)'
                                                                        style="background-color: {{ $hex }};
                    width: 18px;
                    height: 18px;
                    border-radius: 50%;
                    border: 1px solid {{ $border }};
                    {{ $boxShadow }};
                    display: inline-block;
                    cursor: pointer;">
                                                                    </span>
                                                                @endforeach
                                                            </div>

                                                            {{-- Size --}}
                                                            @foreach ($product->sizes as $size)
                                                                <div class="pi01VSItem">
                                                                    <input type="radio"
                                                                        id="size_{{ $product->id }}_{{ $size }}"
                                                                        class="size-picker"
                                                                        data-product-id="{{ $product->id }}"
                                                                        value="{{ strtoupper($size) }}">
                                                                    <label
                                                                        for="size_{{ $product->id }}_{{ $size }}">{{ $size }}</label>
                                                                </div>
                                                            @endforeach


                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="mt-4">
                                            {{ $products->links() }}
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="list-tab-pane" role="tabpanel" aria-labelledby="list-tab"
                                    tabindex="0">
                                    <div class="row">
                                        @foreach ($products as $product)
                                            <div class="col-lg-12 col-xl-6">
                                                <div class="productItem02" data-product-id="{{ $product->id }}">
                                                    <div class="pi02Thumb">
                                                        <img class="main-img"
                                                            src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                                            alt="{{ $product->name }}">
                                                        <img class="hover-img"
                                                            src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                                            alt="{{ $product->name }}">
                                                    </div>

                                                    <div class="pi02Details">
                                                        <h3 class="product-name">
                                                            <a href="{{ route('client.shop.show', $product->slug) }}">
                                                                {{ $product->name }}
                                                            </a>
                                                        </h3>

                                                        <div class="pi01Price">
                                                            <ins class="product-price">
                                                                {{ number_format($product->price) }} VNĐ
                                                            </ins>
                                                        </div>

                                                        <div class="pi01Variations d-flex justify-content-between">

                                                            {{-- Màu sắc --}}
                                                            <div class="pi01VColor d-flex gap-1">
                                                                @php
                                                                    $colorMap = [
                                                                        'do' => '#e74c3c',
                                                                        'xanh' => '#3498db',
                                                                        'trang' => '#ffffff',
                                                                        'den' => '#2c3e50',
                                                                        'vang' => '#f1c40f',
                                                                        'black' => '#2c3e50',
                                                                        'white' => '#ffffff',
                                                                    ];
                                                                @endphp

                                                                @foreach ($product->colors as $color)
                                                                    @php
                                                                        $hex = $colorMap[$color] ?? '#ccc';
                                                                        $border = $hex === '#ffffff' ? '#999' : '#ccc';
                                                                        $boxShadow =
                                                                            $hex === '#ffffff'
                                                                                ? 'box-shadow: 0 0 2px #999;'
                                                                                : '';
                                                                    @endphp

                                                                    <span class="color-picker"
                                                                        data-product-id="{{ $product->id }}"
                                                                        data-color="{{ strtolower(Str::slug($color)) }}"
                                                                        data-map='@json($product->variantMap)'
                                                                        style="background-color: {{ $hex }};
                                            width: 18px; height: 18px;
                                            border-radius: 50%;
                                            border: 1px solid {{ $border }};
                                            {{ $boxShadow }};
                                            display: inline-block;
                                            cursor: pointer;">
                                                                    </span>
                                                                @endforeach
                                                            </div>

                                                            {{-- Kích cỡ --}}
                                                            <div class="pi01VSize d-flex gap-1">
                                                                @foreach ($product->sizes as $index => $size)
                                                                    <div class="pi01VSItem">
                                                                        <input type="radio" class="size-picker"
                                                                            data-product-id="{{ $product->id }}"
                                                                            value="{{ strtoupper($size) }}"
                                                                            name="size_{{ $product->id }}"
                                                                            id="size_{{ $product->id }}_{{ $index }}">
                                                                        <label
                                                                            for="size_{{ $product->id }}_{{ $index }}">
                                                                            {{ $size }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row shopPaginationRow">
                        <div class="col-lg-12 text-center">
                            <div class="shopPagination">
                                <span class="current">1</span>
                                <a href="javascript:void(0);">2</a>
                                <a href="javascript:void(0);">3</a>
                                <a href="javascript:void(0);"><i class="fa-solid fa-angle-right"></i></a>
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
