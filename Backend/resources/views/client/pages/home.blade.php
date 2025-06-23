@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Trang chủ')
@section('css')

@endsection
@include('client.partials.banner')
@section('content')
    <!-- BEGIN: Feature Section -->
    <section class="featureSection">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="iconBox01">
                        <i class="ulina-fast-delivery"></i>
                        <h3>Free Shipping</h3>
                        <p>
                            Ut enim ad minim veniam liquip ami tomader
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="iconBox01">
                        <i class="ulina-credit-card tm5"></i>
                        <h3>Secure Payments</h3>
                        <p>
                            Eonim ad minim veniam liquip tomader
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="iconBox01">
                        <i class="ulina-refund tm1"></i>
                        <h3>Easy Returns</h3>
                        <p>
                            Be enim ad minim veniam liquipa ami tomader
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="iconBox01">
                        <i class="ulina-hours-support t1"></i>
                        <h3>24/7 Support</h3>
                        <p>
                            Ut enim ad minim veniam liquip ami tomader
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Feature Section -->

    <!-- BEGIN: Latest Arrival Section -->
    <section class="latestArrivalSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="secTitle">Latest Arrival</h2>
                    <p class="secDesc">Showing our latest arrival on this summer</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="productCarousel owl-carousel">
                        @foreach ($products as $product)
                            @php
                                $firstVariant = $product->variants->first();
                                $colors = [];
                                $sizes = [];

                                if ($firstVariant && $firstVariant->attributeValues) {
                                    foreach ($firstVariant->attributeValues as $value) {
                                        $attrName = strtolower($value->attribute->name ?? '');

                                        if ($attrName === 'màu sắc') {
                                            $colors[] = $value->value;
                                        }

                                        if ($attrName === 'size') {
                                            $sizes[] = $value->value;
                                        }
                                    }
                                }
                            @endphp


                            <div class="productItem01">
                                <div class="pi01Thumb">
                                    <img src="{{ asset($firstVariant->variant_image ?? $product->product_image) }}"
                                        alt="{{ $product->name }}">
                                    <img src="{{ asset($firstVariant->variant_image ?? $product->product_image) }}"
                                        alt="{{ $product->name }}">
                                    <!-- actions... -->
                                </div>

                                <div class="pi01Details">
                                    <h3>{{ $product->name }}</h3>
                                    <div class="pi01Price">
                                        <ins>{{ number_format($firstVariant->price ?? $product->price) }} VNĐ</ins>
                                    </div>

                                    {{-- Màu sắc --}}
                                    <div class="pi01VColor">
    @php
        $colorMap = [
            'Đỏ' => '#e74c3c',
            'Xanh' => '#3498db',
            'Trắng' => '#ffffff',
            'Đen' => '#2c3e50',
            'Vàng' => '#f1c40f',
        ];
    @endphp

    @foreach ($colors as $colorRaw)
        @php
            $color = mb_convert_case(trim($colorRaw), MB_CASE_TITLE, 'UTF-8');
            $hex = $colorMap[$color] ?? '#ccc';
            $border = $hex === '#ffffff' ? '#999' : '#ccc';
            $boxShadow = $hex === '#ffffff' ? 'box-shadow: 0 0 2px #999;' : '';
        @endphp

        <div style="margin-right: 10px;">
            <label
                style="background-color: {{ $hex }};
                       width: 20px;
                       height: 20px;
                       display: inline-block;
                       border-radius: 50%;
                       border: 1px solid {{ $border }};
                       {{ $boxShadow }}"
                title="{{ $color }}">
            </label>
        </div>
    @endforeach
</div>

                                    {{-- Size --}}
                                    <div class="pi01VSize">
                                        @foreach ($sizes as $size)
                                            <div class="pi01VSItem">
                                                <input type="radio" disabled>
                                                <label>{{ $size }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- END: Latest Arrival Section -->

    <!-- BEGIN: Lookbook Section -->
    <section class="lookbookSection">
        <div class="container">
            <div class="row masonryGrid" id="masonryGrid">
                <div class="col-md-6 col-xl-4 shafItem">
                    <div class="lookBook01 lb01M0 overLayAnim01">
                        <div class="lbContent">
                            <h3>Get 40% Off</h3>
                            <h2>Man’s Latest Collection</h2>
                            <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop
                                Now</a>
                        </div>
                        <img src="{{ asset('client/images/home1/1.png') }}" alt="Mans Latest Collection" />
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 shafItem">
                    <div class="lookBook01 lb01M1 overLayAnim01">
                        <div class="lbContent">
                            <h3>Couple Fashion</h3>
                            <h2>Best Collection for Stylish Couple</h2>
                            <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Explore
                                Now</a>
                        </div>
                        <img src="{{ asset('client/images/home1/2.png') }}" alt="Mans Latest Collection" />
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 shafItem">
                    <div class="lookBook01 lb01M2 overLayAnim01">
                        <div class="lbContent">
                            <h3>Be Stylish</h3>
                            <h2>Girl’s Latest Fashion</h2>
                            <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop
                                Now</a>
                        </div>
                        <img src="{{ asset('client/images/home1/3.png') }}" alt="Mans Latest Collection" />
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 shafItem">
                    <div class="lookBook01 lb01M3 overLayAnim01">
                        <img src="{{ asset('client/images/home1/4.png') }}" alt="Mans Latest Collection" />
                        <div class="lbContent">
                            <h3>New Arrival</h3>
                            <h2>Exclusive Shoes Collection</h2>
                            <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Explore
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 shafItem">
                    <div class="lookBook01 lb01M4 overLayAnim01">
                        <div class="lbContent">
                            <h3>New in 2022</h3>
                            <h2>Discover New Bag Collection</h2>
                            <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Explore
                                Now</a>
                        </div>
                        <img src="{{ asset('client/images/home1/6.png') }}" alt="Mans Latest Collection" />
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 shafItem">
                    <div class="lookBook01 lb01M5 overLayAnim01">
                        <div class="lbContent">
                            <h3>Get 40% Off</h3>
                            <h2>Ulina Trendy Sunglass</h2>
                            <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop
                                Now</a>
                        </div>
                        <img src="{{ asset('client/images/home1/5.png') }}" alt="Mans Latest Collection" />
                    </div>
                </div>
                <div class="col-lg-1 col-sm-1 shafSizer"></div>
            </div>
        </div>
    </section>
    <!-- END: Lookbook Section -->

    <!-- BEGIN: Deal Product Section -->
    <section class="dealProductSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="dealProductContent">
                        <h5>Featured Product</h5>
                        <h2>Ulima Fashionable Jeans</h2>
                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequa uis aute irure dolor </p>
                        <div class="dpcPriceWrap">
                            <div class="pi01Price">
                                <ins>$199</ins>
                                <del>$399</del>
                            </div>
                            <a href="shop_details1.html" class="ulinaBTN"><span>Buy Now</span></a>
                        </div>
                        <div class="countDownWrap">
                            <h6>Ends in</h6>
                            <div class="ulinaCountDown" data-day="26" data-month="07" data-year="2022"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dealProductImage">
                        <img src="{{ asset('client/images/home1/7.png') }}" alt="Ulima Fashionable Jeans" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Deal Product Section -->

    <!-- BEGIN: Popular Products Section -->
    <section class="popularProductsSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="secTitle">Popular Products</h2>
                    <p class="secDesc">Showing our latest arrival on this summer</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="productTabs">
                        <ul class="nav productTabsNav absolutes" id="productTab" role="tablist">
                            <li role="presentation">
                                <button class="active" id="men-tab" data-bs-toggle="tab"
                                    data-bs-target="#men-tab-pane" type="button" role="tab"
                                    aria-controls="men-tab-pane" aria-selected="true">Men</button>
                            </li>
                            <li role="presentation">
                                <button id="women-tab" data-bs-toggle="tab" data-bs-target="#women-tab-pane"
                                    type="button" role="tab" aria-controls="women-tab-pane"
                                    aria-selected="false">Women</button>
                            </li>
                            <li role="presentation">
                                <button id="kids-tab" data-bs-toggle="tab" data-bs-target="#kids-tab-pane"
                                    type="button" role="tab" aria-controls="kids-tab-pane"
                                    aria-selected="false">Kids</button>
                            </li>
                            <li role="presentation">
                                <button id="accessories-tab" data-bs-toggle="tab" data-bs-target="#accessories-tab-pane"
                                    type="button" role="tab" aria-controls="accessories-tab-pane"
                                    aria-selected="false">Accessories</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="productTabContent">
                            <div class="tab-pane fade show active" id="men-tab-pane" role="tabpanel"
                                aria-labelledby="men-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/5.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/5.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">- $29</span>
                                                    <span class="plSale">Sale</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">10 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Stylish white leather bag</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$29</ins>
                                                    <del>$56</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_1_1" value="Blue"
                                                                id="color_1_1_1_blue" />
                                                            <label for="color_1_1_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_1_1" value="Yellow"
                                                                id="color_1_1_2_blue" />
                                                            <label for="color_1_1_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_1_1" value="Red"
                                                                id="color_1_1_3_blue" />
                                                            <label for="color_1_1_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_1" value="Blue"
                                                                id="size1_1_1_1" />
                                                            <label for="size1_1_1_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_1" value="Yellow"
                                                                id="size1_1_1_2" />
                                                            <label for="size1_1_1_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_1" value="Red"
                                                                id="size1_1_1_3" />
                                                            <label for="size1_1_1_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/6.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/6.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plNew float-end">New</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">19 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details1.html">Luxury maroon sweater</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$49</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_1_2" value="Blue"
                                                                id="color_1_2_1_blue" />
                                                            <label for="color_1_2_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_1_2" value="Yellow"
                                                                id="color_1_2_2_blue" />
                                                            <label for="color_1_2_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_1_2" value="Red"
                                                                id="color_1_2_3_blue" />
                                                            <label for="color_1_2_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_2" value="Blue"
                                                                id="size1_1_2_1" />
                                                            <label for="size1_1_2_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_2" value="Yellow"
                                                                id="size1_1_2_2" />
                                                            <label for="size1_1_2_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_2" value="Red"
                                                                id="size1_1_2_3" />
                                                            <label for="size1_1_2_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/7.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/7.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">-89</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details2.html">Lineal louse cotton tops</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$89</ins>
                                                    <del>$99</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_1_3" value="Blue"
                                                                id="color_1_3_1_blue" />
                                                            <label for="color_1_3_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_1_3" value="Yellow"
                                                                id="color_1_3_2_blue" />
                                                            <label for="color_1_3_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_1_3" value="Red"
                                                                id="color_1_3_3_blue" />
                                                            <label for="color_1_3_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_3" value="Blue"
                                                                id="size1_1_3_1" />
                                                            <label for="size1_1_3_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_3" value="Yellow"
                                                                id="size1_1_3_2" />
                                                            <label for="size1_1_3_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_3" value="Red"
                                                                id="size1_1_3_3" />
                                                            <label for="size1_1_3_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/8.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/8.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">13 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Men’s black stylish half shirt</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$129</ins>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_1_4" value="Blue"
                                                                id="color_1_4_1_blue" />
                                                            <label for="color_1_4_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_1_4" value="Yellow"
                                                                id="color_1_4_2_blue" />
                                                            <label for="color_1_4_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_1_4" value="Red"
                                                                id="color_1_4_3_blue" />
                                                            <label for="color_1_4_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_4" value="Blue"
                                                                id="size1_1_4_1" />
                                                            <label for="size1_1_4_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_4" value="Yellow"
                                                                id="size1_1_4_2" />
                                                            <label for="size1_1_4_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_4" value="Red"
                                                                id="size1_1_4_3" />
                                                            <label for="size1_1_4_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/9.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/9.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plHot float-end">Hot</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details1.html">Mini sleeve gray t-shirt</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$39</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_1_5" value="Blue"
                                                                id="color_1_5_1_blue" />
                                                            <label for="color_1_5_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_1_5" value="Yellow"
                                                                id="color_1_5_2_blue" />
                                                            <label for="color_1_5_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_1_5" value="Red"
                                                                id="color_1_5_3_blue" />
                                                            <label for="color_1_5_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_5" value="Blue"
                                                                id="size1_1_5_1" />
                                                            <label for="size1_1_5_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_5" value="Yellow"
                                                                id="size1_1_5_2" />
                                                            <label for="size1_1_5_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_5" value="Red"
                                                                id="size1_1_5_3" />
                                                            <label for="size1_1_5_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/10.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/10.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">18 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Polyester silk blazer suit for men</a>
                                                </h3>
                                                <div class="pi01Price">
                                                    <ins>$499</ins>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_1_6" value="Blue"
                                                                id="color_1_6_1_blue" />
                                                            <label for="color_1_6_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_1_6" value="Yellow"
                                                                id="color_1_6_2_blue" />
                                                            <label for="color_1_6_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_1_6" value="Red"
                                                                id="color_1_6_3_blue" />
                                                            <label for="color_1_6_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_6" value="Blue"
                                                                id="size1_1_6_1" />
                                                            <label for="size1_1_6_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_6" value="Yellow"
                                                                id="size1_1_6_2" />
                                                            <label for="size1_1_6_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_6" value="Red"
                                                                id="size1_1_6_3" />
                                                            <label for="size1_1_6_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/11.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/11.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plSale float-end">sale</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">10 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details1.html">Women’s long cardigans</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$89</ins>
                                                    <del>$99</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_1_7" value="Blue"
                                                                id="color_1_7_1_blue" />
                                                            <label for="color_1_7_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_1_7" value="Yellow"
                                                                id="color_1_7_2_blue" />
                                                            <label for="color_1_7_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_1_7" value="Red"
                                                                id="color_1_7_3_blue" />
                                                            <label for="color_1_7_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_7" value="Blue"
                                                                id="size1_1_7_1" />
                                                            <label for="size1_1_7_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_7" value="Yellow"
                                                                id="size1_1_7_2" />
                                                            <label for="size1_1_7_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_7" value="Red"
                                                                id="size1_1_7_3" />
                                                            <label for="size1_1_7_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/12.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/12.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details2.html">Ulina bag for women</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$49</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_1_8" value="Blue"
                                                                id="color_1_8_1_blue" />
                                                            <label for="color_1_8_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_1_8" value="Yellow"
                                                                id="color_1_8_2_blue" />
                                                            <label for="color_1_8_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_1_8" value="Red"
                                                                id="color_1_8_3_blue" />
                                                            <label for="color_1_8_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_8" value="Blue"
                                                                id="size1_1_8_1" />
                                                            <label for="size1_1_8_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_8" value="Yellow"
                                                                id="size1_1_8_2" />
                                                            <label for="size1_1_8_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_1_8" value="Red"
                                                                id="size1_1_8_3" />
                                                            <label for="size1_1_8_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="women-tab-pane" role="tabpanel" aria-labelledby="women-tab"
                                tabindex="0">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/9.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/9.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plHot float-end">Hot</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details1.html">Mini sleeve gray t-shirt</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$39</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_2_5" value="Blue"
                                                                id="color_2_5_1_blue" />
                                                            <label for="color_2_5_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_2_5" value="Yellow"
                                                                id="color_2_5_2_blue" />
                                                            <label for="color_2_5_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_2_5" value="Red"
                                                                id="color_2_5_3_blue" />
                                                            <label for="color_2_5_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_5" value="Blue"
                                                                id="size1_2_5_1" />
                                                            <label for="size1_2_5_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_5" value="Yellow"
                                                                id="size1_2_5_2" />
                                                            <label for="size1_2_5_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_5" value="Red"
                                                                id="size1_2_5_3" />
                                                            <label for="size1_2_5_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/10.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/10.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">18 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Polyester silk blazer suit for men</a>
                                                </h3>
                                                <div class="pi01Price">
                                                    <ins>$499</ins>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_2_6" value="Blue"
                                                                id="color_2_6_1_blue" />
                                                            <label for="color_2_6_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_2_6" value="Yellow"
                                                                id="color_2_6_2_blue" />
                                                            <label for="color_2_6_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_2_6" value="Red"
                                                                id="color_2_6_3_blue" />
                                                            <label for="color_2_6_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_6" value="Blue"
                                                                id="size1_2_6_1" />
                                                            <label for="size1_2_6_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_6" value="Yellow"
                                                                id="size1_2_6_2" />
                                                            <label for="size1_2_6_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_6" value="Red"
                                                                id="size1_2_6_3" />
                                                            <label for="size1_2_6_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/11.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/11.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plSale float-end">sale</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">10 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details1.html">Women’s long cardigans</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$89</ins>
                                                    <del>$99</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_2_7" value="Blue"
                                                                id="color_2_7_1_blue" />
                                                            <label for="color_2_7_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_2_7" value="Yellow"
                                                                id="color_2_7_2_blue" />
                                                            <label for="color_2_7_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_2_7" value="Red"
                                                                id="color_2_7_3_blue" />
                                                            <label for="color_2_7_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_7" value="Blue"
                                                                id="size1_2_7_1" />
                                                            <label for="size1_2_7_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_7" value="Yellow"
                                                                id="size1_2_7_2" />
                                                            <label for="size1_2_7_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_7" value="Red"
                                                                id="size1_2_7_3" />
                                                            <label for="size1_2_7_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/12.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/12.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details2.html">Ulina bag for women</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$49</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_2_8" value="Blue"
                                                                id="color_2_8_1_blue" />
                                                            <label for="color_2_8_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_2_8" value="Yellow"
                                                                id="color_2_8_2_blue" />
                                                            <label for="color_2_8_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_2_8" value="Red"
                                                                id="color_2_8_3_blue" />
                                                            <label for="color_2_8_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_8" value="Blue"
                                                                id="size1_2_8_1" />
                                                            <label for="size1_2_8_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_8" value="Yellow"
                                                                id="size1_2_8_2" />
                                                            <label for="size1_2_8_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_8" value="Red"
                                                                id="size1_2_8_3" />
                                                            <label for="size1_2_8_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/5.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/5.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">- $29</span>
                                                    <span class="plSale">Sale</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">10 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Stylish white leather bag</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$29</ins>
                                                    <del>$56</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_2_1" value="Blue"
                                                                id="color_2_1_1_blue" />
                                                            <label for="color_2_1_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_2_1" value="Yellow"
                                                                id="color_2_1_2_blue" />
                                                            <label for="color_2_1_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_2_1" value="Red"
                                                                id="color_2_1_3_blue" />
                                                            <label for="color_2_1_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_1" value="Blue"
                                                                id="size1_2_1_1" />
                                                            <label for="size1_2_1_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_1" value="Yellow"
                                                                id="size1_2_1_2" />
                                                            <label for="size1_2_1_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_1" value="Red"
                                                                id="size1_2_1_3" />
                                                            <label for="size1_2_1_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/6.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/6.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plNew float-end">New</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">19 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details1.html">Luxury maroon sweater</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$49</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_2_2"
                                                                value="Blue" id="color_2_2_1_blue" />
                                                            <label for="color_2_2_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_2_2" value="Yellow"
                                                                id="color_2_2_2_blue" />
                                                            <label for="color_2_2_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_2_2" value="Red"
                                                                id="color_2_2_3_blue" />
                                                            <label for="color_2_2_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_2" value="Blue"
                                                                id="size1_2_2_1" />
                                                            <label for="size1_2_2_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_2" value="Yellow"
                                                                id="size1_2_2_2" />
                                                            <label for="size1_2_2_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_2" value="Red"
                                                                id="size1_2_2_3" />
                                                            <label for="size1_2_2_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/7.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/7.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">-89</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details2.html">Lineal louse cotton tops</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$89</ins>
                                                    <del>$99</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_2_3"
                                                                value="Blue" id="color_2_3_1_blue" />
                                                            <label for="color_2_3_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_2_3" value="Yellow"
                                                                id="color_2_3_2_blue" />
                                                            <label for="color_2_3_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_2_3" value="Red"
                                                                id="color_2_3_3_blue" />
                                                            <label for="color_2_3_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_3" value="Blue"
                                                                id="size1_2_3_1" />
                                                            <label for="size1_2_3_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_3" value="Yellow"
                                                                id="size1_2_3_2" />
                                                            <label for="size1_2_3_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_3" value="Red"
                                                                id="size1_2_3_3" />
                                                            <label for="size1_2_3_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/8.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/8.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">13 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Men’s black stylish half shirt</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$129</ins>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_2_4"
                                                                value="Blue" id="color_2_4_1_blue" />
                                                            <label for="color_2_4_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_2_4" value="Yellow"
                                                                id="color_2_4_2_blue" />
                                                            <label for="color_2_4_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_2_4" value="Red"
                                                                id="color_2_4_3_blue" />
                                                            <label for="color_2_4_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_4" value="Blue"
                                                                id="size1_2_4_1" />
                                                            <label for="size1_2_4_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_4" value="Yellow"
                                                                id="size1_2_4_2" />
                                                            <label for="size1_2_4_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_2_4" value="Red"
                                                                id="size1_2_4_3" />
                                                            <label for="size1_2_4_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="kids-tab-pane" role="tabpanel" aria-labelledby="kids-tab"
                                tabindex="0">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/7.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/7.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">-89</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details1.html">Lineal louse cotton tops</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$89</ins>
                                                    <del>$99</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_3_3"
                                                                value="Blue" id="color_3_3_1_blue" />
                                                            <label for="color_3_3_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_3_3" value="Yellow"
                                                                id="color_3_3_2_blue" />
                                                            <label for="color_3_3_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_3_3" value="Red"
                                                                id="color_3_3_3_blue" />
                                                            <label for="color_3_3_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_3" value="Blue"
                                                                id="size1_3_3_1" />
                                                            <label for="size1_3_3_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_3" value="Yellow"
                                                                id="size1_3_3_2" />
                                                            <label for="size1_3_3_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_3" value="Red"
                                                                id="size1_3_3_3" />
                                                            <label for="size1_3_3_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/8.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/8.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">13 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Men’s black stylish half shirt</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$129</ins>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_3_4"
                                                                value="Blue" id="color_3_4_1_blue" />
                                                            <label for="color_3_4_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_3_4" value="Yellow"
                                                                id="color_3_4_2_blue" />
                                                            <label for="color_3_4_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_3_4" value="Red"
                                                                id="color_3_4_3_blue" />
                                                            <label for="color_3_4_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_4" value="Blue"
                                                                id="size1_3_4_1" />
                                                            <label for="size1_3_4_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_4" value="Yellow"
                                                                id="size1_3_4_2" />
                                                            <label for="size1_3_4_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_4" value="Red"
                                                                id="size1_3_4_3" />
                                                            <label for="size1_3_4_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/5.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/5.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">- $29</span>
                                                    <span class="plSale">Sale</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">10 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details1.html">Stylish white leather bag</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$29</ins>
                                                    <del>$56</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_3_1"
                                                                value="Blue" id="color_3_1_1_blue" />
                                                            <label for="color_3_1_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_3_1" value="Yellow"
                                                                id="color_3_1_2_blue" />
                                                            <label for="color_3_1_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_3_1" value="Red"
                                                                id="color_3_1_3_blue" />
                                                            <label for="color_3_1_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_1" value="Blue"
                                                                id="size1_3_1_1" />
                                                            <label for="size1_3_1_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_1" value="Yellow"
                                                                id="size1_3_1_2" />
                                                            <label for="size1_3_1_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_1" value="Red"
                                                                id="size1_3_1_3" />
                                                            <label for="size1_3_1_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/6.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/6.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plNew float-end">New</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">19 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Luxury maroon sweater</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$49</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_3_2"
                                                                value="Blue" id="color_3_2_1_blue" />
                                                            <label for="color_3_2_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_3_2" value="Yellow"
                                                                id="color_3_2_2_blue" />
                                                            <label for="color_3_2_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_3_2" value="Red"
                                                                id="color_3_2_3_blue" />
                                                            <label for="color_3_2_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_2" value="Blue"
                                                                id="size1_3_2_1" />
                                                            <label for="size1_3_2_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_2" value="Yellow"
                                                                id="size1_3_2_2" />
                                                            <label for="size1_3_2_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_2" value="Red"
                                                                id="size1_3_2_3" />
                                                            <label for="size1_3_2_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/11.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/11.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plSale float-end">sale</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">10 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details1.html">Women’s long cardigans</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$89</ins>
                                                    <del>$99</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_3_7"
                                                                value="Blue" id="color_3_7_1_blue" />
                                                            <label for="color_3_7_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_3_7" value="Yellow"
                                                                id="color_3_7_2_blue" />
                                                            <label for="color_3_7_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_3_7" value="Red"
                                                                id="color_3_7_3_blue" />
                                                            <label for="color_3_7_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_7" value="Blue"
                                                                id="size1_3_7_1" />
                                                            <label for="size1_3_7_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_7" value="Yellow"
                                                                id="size1_3_7_2" />
                                                            <label for="size1_3_7_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_7" value="Red"
                                                                id="size1_3_7_3" />
                                                            <label for="size1_3_7_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/12.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/12.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details2.html">Ulina bag for women</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$49</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_3_8"
                                                                value="Blue" id="color_3_8_1_blue" />
                                                            <label for="color_3_8_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_3_8" value="Yellow"
                                                                id="color_3_8_2_blue" />
                                                            <label for="color_3_8_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_3_8" value="Red"
                                                                id="color_3_8_3_blue" />
                                                            <label for="color_3_8_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_8" value="Blue"
                                                                id="size1_3_8_1" />
                                                            <label for="size1_3_8_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_8" value="Yellow"
                                                                id="size1_3_8_2" />
                                                            <label for="size1_3_8_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_8" value="Red"
                                                                id="size1_3_8_3" />
                                                            <label for="size1_3_8_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/9.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/9.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plHot float-end">Hot</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details1.html">Mini sleeve gray t-shirt</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$39</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_3_5"
                                                                value="Blue" id="color_3_5_1_blue" />
                                                            <label for="color_3_5_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_3_5" value="Yellow"
                                                                id="color_3_5_2_blue" />
                                                            <label for="color_3_5_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_3_5" value="Red"
                                                                id="color_3_5_3_blue" />
                                                            <label for="color_3_5_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_5" value="Blue"
                                                                id="size1_3_5_1" />
                                                            <label for="size1_3_5_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_5" value="Yellow"
                                                                id="size1_3_5_2" />
                                                            <label for="size1_3_5_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_5" value="Red"
                                                                id="size1_3_5_3" />
                                                            <label for="size1_3_5_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/10.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/10.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">18 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Polyester silk blazer suit for men</a>
                                                </h3>
                                                <div class="pi01Price">
                                                    <ins>$499</ins>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_3_6"
                                                                value="Blue" id="color_3_6_1_blue" />
                                                            <label for="color_3_6_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_3_6" value="Yellow"
                                                                id="color_3_6_2_blue" />
                                                            <label for="color_3_6_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_3_6" value="Red"
                                                                id="color_3_6_3_blue" />
                                                            <label for="color_3_6_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_6" value="Blue"
                                                                id="size1_3_6_1" />
                                                            <label for="size1_3_6_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_6" value="Yellow"
                                                                id="size1_3_6_2" />
                                                            <label for="size1_3_6_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_3_6" value="Red"
                                                                id="size1_3_6_3" />
                                                            <label for="size1_3_6_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="accessories-tab-pane" role="tabpanel"
                                aria-labelledby="accessories-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/7.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/7.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">-89</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details1.html">Lineal louse cotton tops</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$89</ins>
                                                    <del>$99</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_4_3"
                                                                value="Blue" id="color_4_3_1_blue" />
                                                            <label for="color_4_3_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_4_3" value="Yellow"
                                                                id="color_4_3_2_blue" />
                                                            <label for="color_4_3_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_4_3" value="Red"
                                                                id="color_4_3_3_blue" />
                                                            <label for="color_4_3_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_3" value="Blue"
                                                                id="size1_4_3_1" />
                                                            <label for="size1_4_3_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_3" value="Yellow"
                                                                id="size1_4_3_2" />
                                                            <label for="size1_4_3_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_3" value="Red"
                                                                id="size1_4_3_3" />
                                                            <label for="size1_4_3_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/5.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/5.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">- $29</span>
                                                    <span class="plSale">Sale</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">10 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details1.html">Stylish white leather bag</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$29</ins>
                                                    <del>$56</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_4_1"
                                                                value="Blue" id="color_4_1_1_blue" />
                                                            <label for="color_4_1_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_4_1" value="Yellow"
                                                                id="color_4_1_2_blue" />
                                                            <label for="color_4_1_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_4_1" value="Red"
                                                                id="color_4_1_3_blue" />
                                                            <label for="color_4_1_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_1" value="Blue"
                                                                id="size1_4_1_1" />
                                                            <label for="size1_4_1_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_1" value="Yellow"
                                                                id="size1_4_1_2" />
                                                            <label for="size1_4_1_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_1" value="Red"
                                                                id="size1_4_1_3" />
                                                            <label for="size1_4_1_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/6.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/6.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plNew float-end">New</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">19 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Luxury maroon sweater</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$49</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_4_2"
                                                                value="Blue" id="color_4_2_1_blue" />
                                                            <label for="color_4_2_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_4_2" value="Yellow"
                                                                id="color_4_2_2_blue" />
                                                            <label for="color_4_2_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_4_2" value="Red"
                                                                id="color_4_2_3_blue" />
                                                            <label for="color_4_2_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_2" value="Blue"
                                                                id="size1_4_2_1" />
                                                            <label for="size1_4_2_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_2" value="Yellow"
                                                                id="size1_4_2_2" />
                                                            <label for="size1_4_2_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_2" value="Red"
                                                                id="size1_4_2_3" />
                                                            <label for="size1_4_2_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/8.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/8.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">13 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Men’s black stylish half shirt</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$129</ins>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_4_4"
                                                                value="Blue" id="color_4_4_1_blue" />
                                                            <label for="color_4_4_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_4_4" value="Yellow"
                                                                id="color_4_4_2_blue" />
                                                            <label for="color_4_4_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_4_4" value="Red"
                                                                id="color_4_4_3_blue" />
                                                            <label for="color_4_4_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_4" value="Blue"
                                                                id="size1_4_4_1" />
                                                            <label for="size1_4_4_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_4" value="Yellow"
                                                                id="size1_4_4_2" />
                                                            <label for="size1_4_4_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_4" value="Red"
                                                                id="size1_4_4_3" />
                                                            <label for="size1_4_4_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/11.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/11.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plSale float-end">sale</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">10 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Women’s long cardigans</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$89</ins>
                                                    <del>$99</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_4_7"
                                                                value="Blue" id="color_4_7_1_blue" />
                                                            <label for="color_4_7_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_4_7" value="Yellow"
                                                                id="color_4_7_2_blue" />
                                                            <label for="color_4_7_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_4_7" value="Red"
                                                                id="color_4_7_3_blue" />
                                                            <label for="color_4_7_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_7" value="Blue"
                                                                id="size1_4_7_1" />
                                                            <label for="size1_4_7_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_7" value="Yellow"
                                                                id="size1_4_7_2" />
                                                            <label for="size1_4_7_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_7" value="Red"
                                                                id="size1_4_7_3" />
                                                            <label for="size1_4_7_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/12.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/12.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details2.html">Ulina bag for women</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$49</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_4_8"
                                                                value="Blue" id="color_4_8_1_blue" />
                                                            <label for="color_4_8_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_4_8" value="Yellow"
                                                                id="color_4_8_2_blue" />
                                                            <label for="color_4_8_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_4_8" value="Red"
                                                                id="color_4_8_3_blue" />
                                                            <label for="color_4_8_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_8" value="Blue"
                                                                id="size1_4_8_1" />
                                                            <label for="size1_4_8_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_8" value="Yellow"
                                                                id="size1_4_8_2" />
                                                            <label for="size1_4_8_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_8" value="Red"
                                                                id="size1_4_8_3" />
                                                            <label for="size1_4_8_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/9.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/9.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plHot float-end">Hot</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="shop_details2.html">Mini sleeve gray t-shirt</a></h3>
                                                <div class="pi01Price">
                                                    <ins>$39</ins>
                                                    <del>$60</del>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_4_5"
                                                                value="Blue" id="color_4_5_1_blue" />
                                                            <label for="color_4_5_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_4_5" value="Yellow"
                                                                id="color_4_5_2_blue" />
                                                            <label for="color_4_5_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_4_5" value="Red"
                                                                id="color_4_5_3_blue" />
                                                            <label for="color_4_5_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_5" value="Blue"
                                                                id="size1_4_5_1" />
                                                            <label for="size1_4_5_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_5" value="Yellow"
                                                                id="size1_4_5_2" />
                                                            <label for="size1_4_5_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_5" value="Red"
                                                                id="size1_4_5_3" />
                                                            <label for="size1_4_5_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ asset('client/images/products/10.jpg') }}"
                                                    alt="Ulina Product" />
                                                <img src="{{ asset('client/images/products/10.1.jpg') }}"
                                                    alt="Ulina Product" />
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" class="pi01Cart"><i
                                                            class="fa-solid fa-shopping-cart"></i></a>
                                                    <a href="javascript:void(0);" class="pi01QuickView"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                    <a href="javascript:void(0);" class="pi01Wishlist"><i
                                                            class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <div class="productRatings">
                                                    <div class="productRatingWrap">
                                                        <div class="star-rating"><span></span></div>
                                                    </div>
                                                    <div class="ratingCounts">18 Reviews</div>
                                                </div>
                                                <h3><a href="shop_details2.html">Polyester silk blazer suit for men</a>
                                                </h3>
                                                <div class="pi01Price">
                                                    <ins>$499</ins>
                                                </div>
                                                <div class="pi01Variations">
                                                    <div class="pi01VColor">
                                                        <div class="pi01VCItem">
                                                            <input checked type="radio" name="color_4_6"
                                                                value="Blue" id="color_4_6_1_blue" />
                                                            <label for="color_4_6_1_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem yellows">
                                                            <input type="radio" name="color_4_6" value="Yellow"
                                                                id="color_4_6_2_blue" />
                                                            <label for="color_4_6_2_blue"></label>
                                                        </div>
                                                        <div class="pi01VCItem reds">
                                                            <input type="radio" name="color_4_6" value="Red"
                                                                id="color_4_645678_3_blue" />
                                                            <label for="color_4_645678_3_blue"></label>
                                                        </div>
                                                    </div>
                                                    <div class="pi01VSize">
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_6" value="Blue"
                                                                id="size1_4_6_1" />
                                                            <label for="size1_4_6_1">S</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_6" value="Yellow"
                                                                id="size1_4_6_2" />
                                                            <label for="size1_4_6_2">M</label>
                                                        </div>
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_4_6" value="Red"
                                                                id="size1_4_6_3" />
                                                            <label for="size1_4_6_3">XL</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    <!-- END: Popular Products Section -->

    <!-- BEGIN: Lookbook Section 2 -->
    <section class="lookbookSection2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="lookBook01 lb01M6 overLayAnim02">
                        <div class="lbContent">
                            <h3>Get 40% Off</h3>
                            <h2>Women’s New Collection</h2>
                            <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop
                                Now</a>
                        </div>
                        <img src="{{ asset('client/images/home1/8.jpg') }}" alt="Women’s New Collection">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="lookBook01 lb01M6 overLayAnim02">
                        <div class="lbContent">
                            <h3>Stay Upto Date</h3>
                            <h2>Men’s Trendy Fashion</h2>
                            <a href="collections.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Shop
                                Now</a>
                        </div>
                        <img src="{{ asset('client/images/home1/9.jpg') }}" alt="Men’s Trendy Fashion">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Lookbook Section 2 -->

    <!-- BEGIN: Category Section -->
    <section class="categorySection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="secTitle">Shop By Category</h2>
                    <p class="secDesc">Showing our latest arrival on this summer</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="categoryCarousel owl-carousel">
                        <div class="categoryItem01 text-center">
                            <div class="ci01Thumb">
                                <img src="{{ asset('client/images/categoy/1.jpg') }}" alt="Cloths" />
                            </div>
                            <h3><a href="shop_full_width.html">Cloths</a></h3>
                            <p>192 Items</p>
                        </div>
                        <div class="categoryItem01 text-center">
                            <div class="ci01Thumb">
                                <img src="{{ asset('client/images/categoy/2.jpg') }}" alt="Bags" />
                            </div>
                            <h3><a href="shop_left_sidebar.html">Bags</a></h3>
                            <p>139 Items</p>
                        </div>
                        <div class="categoryItem01 text-center">
                            <div class="ci01Thumb">
                                <img src="{{ asset('client/images/categoy/3.jpg') }}" alt="Watches" />
                            </div>
                            <h3><a href="shop_right_sidebar.html">Watches</a></h3>
                            <p>162 Items</p>
                        </div>
                        <div class="categoryItem01 text-center">
                            <div class="ci01Thumb">
                                <img src="{{ asset('client/images/categoy/4.jpg') }}" alt="Jewellery" />
                            </div>
                            <h3><a href="shop_full_width.html">Jewellery</a></h3>
                            <p>187 Items</p>
                        </div>
                        <div class="categoryItem01 text-center">
                            <div class="ci01Thumb">
                                <img src="{{ asset('client/images/categoy/5.jpg') }}" alt="Women" />
                            </div>
                            <h3><a href="shop_right_sidebar.html">Women</a></h3>
                            <p>362 Items</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Category Section -->

    <!-- BEGIN: Testimonial Section -->
    <section class="testimonialSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3">
                    <div class="testimoniLeft">
                        <h2 class="secTitle">What Customers Say About Us</h2>
                        <p class="secDesc">Bobore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat
                            ion ullamco laboris</p>
                        <div class="testimonalNav">
                            <button class="tprev"><i class="fa-solid fa-angle-left"></i></button>
                            <button class="tnext"><i class="fa-solid fa-angle-right"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="testimonialSliderWrap">
                        <div class="testimonialCarousel owl-carousel">
                            <div class="testimonialItem01">
                                <div class="ti01Header clearfix">
                                    <i class="ulina-quote"></i>
                                    <div class="ti01Rating float-end">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <div class="ti01Content">
                                    Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor
                                    incididunt ut labore et doleore magna
                                    aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut
                                    aliquip.
                                </div>
                                <div class="ti01Author">
                                    <img src="{{ asset('client/images/author/1.jpg') }}" alt="Sanjida Ema" />
                                    <h3>Sanjida Ema</h3>
                                    <span>Journalist</span>
                                </div>
                            </div>
                            <div class="testimonialItem01">
                                <div class="ti01Header clearfix">
                                    <i class="ulina-quote"></i>
                                    <div class="ti01Rating float-end">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <div class="ti01Content">
                                    Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor
                                    incididunt ut labore et doleore magna
                                    aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut
                                    aliquip.
                                </div>
                                <div class="ti01Author">
                                    <img src="{{ asset('client/images/author/2.jpg') }}" alt="Mike Anderson" />
                                    <h3>Mike Anderson</h3>
                                    <span>Web Developer</span>
                                </div>
                            </div>
                            <div class="testimonialItem01">
                                <div class="ti01Header clearfix">
                                    <i class="ulina-quote"></i>
                                    <div class="ti01Rating float-end">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <div class="ti01Content">
                                    Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor
                                    incididunt ut labore et doleore magna
                                    aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut
                                    aliquip.
                                </div>
                                <div class="ti01Author">
                                    <img src="{{ asset('client/images/author/3.png') }}" alt="Nelson Rich" />
                                    <h3>Nelson Rich</h3>
                                    <span>Designer</span>
                                </div>
                            </div>
                            <div class="testimonialItem01">
                                <div class="ti01Header clearfix">
                                    <i class="ulina-quote"></i>
                                    <div class="ti01Rating float-end">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <div class="ti01Content">
                                    Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor
                                    incididunt ut labore et doleore magna
                                    aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut
                                    aliquip.
                                </div>
                                <div class="ti01Author">
                                    <img src="{{ asset('client/images/author/4.png') }}" alt="Nelson Rich" />
                                    <h3>Mark Smith</h3>
                                    <span>Marketer</span>
                                </div>
                            </div>
                            <div class="testimonialItem01">
                                <div class="ti01Header clearfix">
                                    <i class="ulina-quote"></i>
                                    <div class="ti01Rating float-end">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <div class="ti01Content">
                                    Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor
                                    incididunt ut labore et doleore magna
                                    aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut
                                    aliquip.
                                </div>
                                <div class="ti01Author">
                                    <img src="{{ asset('client/images/author/5.png') }}" alt="Sarah Jones" />
                                    <h3>Sarah Jones</h3>
                                    <span>Researcher</span>
                                </div>
                            </div>
                            <div class="testimonialItem01">
                                <div class="ti01Header clearfix">
                                    <i class="ulina-quote"></i>
                                    <div class="ti01Rating float-end">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <div class="ti01Content">
                                    Lorem ipsum dolor sit amet, conseet eotur adipisicing elit, sed do eiusmoed tempor
                                    incididunt ut labore et doleore magna
                                    aliqua. Ut enim ad mire oknim veniam, quis nostrud exercitation ull mco laboris nisi ut
                                    aliquip.
                                </div>
                                <div class="ti01Author">
                                    <img src="{{ asset('client/images/author/6.png') }}" alt="Sarah Jones" />
                                    <h3>John Anderson</h3>
                                    <span>Blogger</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Testimonial Section -->

    <!-- BEGIN: Blog Section -->
    <section class="blogSection">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="secTitle">Latest News</h2>
                    <p class="secDesc">Showing our latest arrival on this summer</p>
                </div>
                <div class="col-md-6 text-end pdt34">
                    <a href="blog_grid_nsb.html" class="ulinaBTN2"><span>View All</span></a>
                </div>
            </div>
            <div class="row masonryGrid" id="masonryGrid2">
                <div class="col-lg-8 col-xl-6 shafItem">
                    <div class="blogItem01">
                        <img src="{{ asset('client/images/news/1.jpg') }}" alt="Ulina News" />
                        <div class="bi01Content">
                            <div class="bi01Meta clearfix">
                                <span><i class="fa-solid fa-folder-open"></i><a
                                        href="blog_details_lsb.html">Shopping</a></span>
                                <span><i class="fa-solid fa-clock"></i><a href="blog_grid_lsb.html">May 31,
                                        2022</a></span>
                                <span><i class="fa-solid fa-user"></i><a href="blog_standard_nsb.html">Jewel
                                        Khan</a></span>
                            </div>
                            <h3><a href="blog_details_rsb.html">When the musics over turn off the light</a></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 shafItem">
                    <div class="blogItem02">
                        <div class="bi01Meta clearfix">
                            <span><i class="fa-solid fa-folder-open"></i><a
                                    href="blog_details_nsb.html">Shopping</a></span>
                            <span><i class="fa-solid fa-clock"></i><a href="blog_details_nsb.html">May 31,
                                    2022</a></span>
                        </div>
                        <h3><a href="blog_details_rsb.html">When the musics over turn off the light</a></h3>
                        <a href="blog_details_nsb.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Read
                            More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 shafItem">
                    <div class="blogItem02">
                        <div class="bi01Meta clearfix">
                            <span><i class="fa-solid fa-folder-open"></i><a
                                    href="blog_details_rsb.html">Shopping</a></span>
                            <span><i class="fa-solid fa-clock"></i><a href="blog_details_rsb.html">May 31,
                                    2022</a></span>
                        </div>
                        <h3><a href="blog_details_rsb.html">When the musics over turn off the light</a></h3>
                        <a href="blog_details_rsb.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Read
                            More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 shafItem">
                    <div class="blogItem02">
                        <div class="bi01Meta clearfix">
                            <span><i class="fa-solid fa-folder-open"></i><a
                                    href="blog_details_nsb.html">Shopping</a></span>
                            <span><i class="fa-solid fa-clock"></i><a href="blog_grid_lsb.html">May 31, 2022</a></span>
                        </div>
                        <h3><a href="blog_details_rsb.html">When the musics over turn off the light</a></h3>
                        <a href="blog_details_nsb.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Read
                            More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 shafItem">
                    <div class="blogItem02">
                        <div class="bi01Meta clearfix">
                            <span><i class="fa-solid fa-folder-open"></i><a
                                    href="blog_details_rsb.html">Shopping</a></span>
                            <span><i class="fa-solid fa-clock"></i><a href="blog_grid_rsb.html">May 31, 2022</a></span>
                        </div>
                        <h3><a href="blog_details_rsb.html">When the musics over turn off the light</a></h3>
                        <a href="blog_details_lsb.html" class="ulinaLink"><i class="fa-solid fa-angle-right"></i>Read
                            More</a>
                    </div>
                </div>
                <div class="col-lg-1 col-sm-1 shafSizer"></div>
            </div>
        </div>
    </section>
    <!-- END: Blog Section -->

    <!-- BEGIN: Instagram Section -->
    <section class="instagramSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="secTitle">Follow Us in Instagram @Ulina</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="instagramSlider owl-carousel">
                        <a href="#" class="instagramPhoto imgPopup">
                            <img src="{{ asset('client/images/instagram/1.jpg') }}" alt="Ulina Instagram" />
                        </a>
                        <a href="#" class="instagramPhoto imgPopup">
                            <img src="{{ asset('client/images/instagram/2.jpg') }}" alt="Ulina Instagram" />
                        </a>
                        <a href="#" class="instagramPhoto imgPopup">
                            <img src="{{ asset('client/images/instagram/3.jpg') }}" alt="Ulina Instagram" />
                        </a>
                        <a href="#" class="instagramPhoto imgPopup">
                            <img src="{{ asset('client/images/instagram/4.jpg') }}" alt="Ulina Instagram" />
                        </a>
                        <a href="#" class="instagramPhoto imgPopup">
                            <img src="{{ asset('client/images/instagram/5.jpg') }}" alt="Ulina Instagram" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Instagram Section -->

    <!-- BEGIN: Brand Section -->
    <section class="brandSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="clientLogoSlider owl-carousel">
                        <a class="clientLogo" href="javascript:void(0);">
                            <img src="{{ asset('client/images/clients/1h.png') }}" alt="Ulina Brand">
                            <img src="{{ asset('client/images/clients/1.png') }}" alt="Ulina Brand">
                        </a>
                        <a class="clientLogo" href="javascript:void(0);">
                            <img src="{{ asset('client/images/clients/2h.png') }}" alt="Ulina Brand">
                            <img src="{{ asset('client/images/clients/2.png') }}" alt="Ulina Brand">
                        </a>
                        <a class="clientLogo" href="javascript:void(0);">
                            <img src="{{ asset('client/images/clients/3h.png') }}" alt="Ulina Brand">
                            <img src="{{ asset('client/images/clients/3.png') }}" alt="Ulina Brand">
                        </a>
                        <a class="clientLogo" href="javascript:void(0);">
                            <img src="{{ asset('client/images/clients/4h.png') }}" alt="Ulina Brand">
                            <img src="{{ asset('client/images/clients/4.png') }}" alt="Ulina Brand">
                        </a>
                        <a class="clientLogo" href="javascript:void(0);">
                            <img src="{{ asset('client/images/clients/5h.png') }}" alt="Ulina Brand">
                            <img src="{{ asset('client/images/clients/5.png') }}" alt="Ulina Brand">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Brand Section -->


    <!-- BEGIN: Back To Top -->
    <a href="javascript:void(0);" id="backtotop"><i class="fa-solid fa-angles-up"></i></a>
    <!-- END: Back To Top -->

    <!-- BEGIN: Product QuickView  -->
    <div class="modal fade productQuickView" id="productQuickView" tabindex="-1"
        data-aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="quickViewCloser" data-bs-dismiss="modal"
                    aria-label="Close"><span></span></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="productGalleryWrap">
                                <div class="productGalleryPopup">
                                    <div class="pgImage">
                                        <img src="{{ asset('client/images/product_details/p1.jpg') }}"
                                            alt="Product Image" />
                                    </div>
                                    <div class="pgImage">
                                        <img src="{{ asset('client/images/product_details/p2.jpg') }}"
                                            alt="Product Image" />
                                    </div>
                                    <div class="pgImage">
                                        <img src="{{ asset('client/images/product_details/p3.jpg') }}"
                                            alt="Product Image" />
                                    </div>
                                    <div class="pgImage">
                                        <img src="{{ asset('client/images/product_details/p4.jpg') }}"
                                            alt="Product Image" />
                                    </div>
                                    <div class="pgImage">
                                        <img src="{{ asset('client/images/product_details/p5.jpg') }}"
                                            alt="Product Image" />
                                    </div>
                                </div>
                                <div class="productGalleryThumbWrap">
                                    <div class="productGalleryThumbPopup">
                                        <div class="pgtImage">
                                            <img src="{{ asset('client/images/product_details/t1.jpg') }}"
                                                alt="Product Image" />
                                        </div>
                                        <div class="pgtImage">
                                            <img src="{{ asset('client/images/product_details/t2.jpg') }}"
                                                alt="Product Image" />
                                        </div>
                                        <div class="pgtImage">
                                            <img src="{{ asset('client/images/product_details/t3.jpg') }}"
                                                alt="Product Image" />
                                        </div>
                                        <div class="pgtImage">
                                            <img src="{{ asset('client/images/product_details/t4.jpg') }}"
                                                alt="Product Image" />
                                        </div>
                                        <div class="pgtImage">
                                            <img src="{{ asset('client/images/product_details/t5.jpg') }}"
                                                alt="Product Image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="productContent">
                                <div class="pcCategory">
                                    <a href="shop_right_sidebar.html">Fashion</a>, <a
                                        href="shop_left_sidebar.html">Sports</a>
                                </div>
                                <h2><a href="shop_details1.html">Ulina luxurious shirt for men</a></h2>
                                <div class="pi01Price">
                                    <ins>$108</ins>
                                    <del>$120</del>
                                </div>
                                <div class="productRadingsStock clearfix">
                                    <div class="productRatings float-start">
                                        <div class="productRatingWrap">
                                            <div class="star-rating"><span></span></div>
                                        </div>
                                        <div class="ratingCounts">52 Reviews</div>
                                    </div>
                                    <div class="productStock float-end">
                                        <span>Available :</span> 12
                                    </div>
                                </div>
                                <div class="pcExcerpt">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusncididunt ut labo
                                    re et dolore magna aliqua. Ut enim ad minim
                                    veniam
                                </div>
                                <div class="pcVariations">
                                    <div class="pcVariation">
                                        <span>Color</span>
                                        <div class="pcvContainer">
                                            <div class="pi01VCItem">
                                                <input checked type="radio" name="color_4_6" value="Blue"
                                                    id="color_4_634_1_blue" />
                                                <label for="color_4_634_1_blue"></label>
                                            </div>
                                            <div class="pi01VCItem yellows">
                                                <input type="radio" name="color_4_6" value="Yellow"
                                                    id="color_4_6sdf_2_blue" />
                                                <label for="color_4_6sdf_2_blue"></label>
                                            </div>
                                            <div class="pi01VCItem reds">
                                                <input type="radio" name="color_4_6" value="Red"
                                                    id="color_4_6_3_blue" />
                                                <label for="color_4_6_3_blue"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pcVariation pcv2">
                                        <span>Size</span>
                                        <div class="pcvContainer">
                                            <div class="pswItem">
                                                <input checked="" type="radio" name="ws_1" value="S"
                                                    id="ws_1_s">
                                                <label for="ws_1_s">S</label>
                                            </div>
                                            <div class="pswItem">
                                                <input type="radio" name="ws_1" value="M" id="ws_1_m">
                                                <label for="ws_1_m">M</label>
                                            </div>
                                            <div class="pswItem">
                                                <input type="radio" name="ws_1" value="L" id="ws_1_l">
                                                <label for="ws_1_l">L</label>
                                            </div>
                                            <div class="pswItem">
                                                <input type="radio" name="ws_1" value="XL" id="ws_1_xl">
                                                <label for="ws_1_xl">XL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pcBtns">
                                    <div class="quantity clearfix">
                                        <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                                        <input type="number" class="carqty input-text qty text" name="quantity"
                                            value="01">
                                        <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                                    </div>
                                    <button type="submit" class="ulinaBTN"><span>Add to Cart</span></button>
                                </div>
                                <div class="pcMeta">
                                    <p>
                                        <span>Sku</span>
                                        <a href="javascript:void(0);">3489 JE0765</a>
                                    </p>
                                    <p class="pcmTags">
                                        <span>Tags:</span>
                                        <a href="javascript:void(0);">Fashion</a>, <a
                                            href="javascript:void(0);">Bags</a>, <a href="javascript:void(0);">Girls</a>
                                    </p>
                                    <p class="pcmSocial">
                                        <span>Share</span>
                                        <a class="fac" href="javascript:void(0);"><i
                                                class="fa-brands fa-facebook-f"></i></a>
                                        <a class="twi" href="javascript:void(0);"><i
                                                class="fa-brands fa-twitter"></i></a>
                                        <a class="lin" href="javascript:void(0);"><i
                                                class="fa-brands fa-linkedin-in"></i></a>
                                        <a class="ins" href="javascript:void(0);"><i
                                                class="fa-brands fa-instagram"></i></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Product QuickView -->
@endsection
@section('script')

@endsection
