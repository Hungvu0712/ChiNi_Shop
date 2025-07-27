@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Trang chủ')
@section('css')
    <style>
        /* Nút hành động sản phẩm */
        .pi01Actions a {
            width: 40px;
            height: 40px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            transition: all 0.3s ease;
            font-size: 16px;
            margin: 0 5px;
        }

        .pi01Actions a:hover {
            background-color: #7b9691;
            color: #fffeff;
        }

        /* Nút mạng xã hội ở footer */
        .footerSocial a {
            width: 40px;
            height: 40px;
            background-color: #fff;
            color: #333;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            margin: 0 5px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .footerSocial a:hover {
            background-color: #7b9691;
            color: #fffeff;
        }

        /* Ảnh sản phẩm chính */
        .product-preview,
        .main-image {
            transition: opacity 0.3s ease-in-out;
        }

        /* Chấm màu */
        .color-picker {
            width: 16px;
            height: 16px;
            display: inline-block;
            border-radius: 50%;
            border: 1px solid #dee2e6;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .color-picker:hover {
            transform: scale(1.2);
            border-color: #333;
        }

        .color-picker.active {
            outline: 2px solid black;
            outline-offset: 1px;
        }

        /* Nút thuộc tính (size, chất liệu,...) */
        .attribute-item {
            display: inline-block;
            padding: 4px 12px;
            font-size: 14px;
            background-color: #f8f8f8;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .attribute-item:hover {
            background-color: #000;
            color: #fff;
            border-color: #000;
        }

        /* Layout hiển thị màu + thuộc tính cùng dòng */
        .variant-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            /* để xuống dòng nếu không đủ */
            gap: 0.5rem;
        }

        .variant-right {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Responsive fix cho mobile nếu cần */
        @media (max-width: 576px) {
            .variant-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

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
                        <h3>Miễn phí vận chuyển</h3>
                        <p>
                            Giao hàng nhanh chóng và hoàn toàn miễn phí cho mọi đơn hàng.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="iconBox01">
                        <i class="ulina-credit-card tm5"></i>
                        <h3>Thanh toán an toàn</h3>
                        <p>
                            Hỗ trợ nhiều phương thức thanh toán bảo mật và tiện lợi.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="iconBox01">
                        <i class="ulina-refund tm1"></i>
                        <h3>Đổi trả dễ dàng</h3>
                        <p>
                            Đổi trả sản phẩm nhanh chóng trong vòng 7 ngày nếu có lỗi.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="iconBox01">
                        <i class="ulina-hours-support t1"></i>
                        <h3>Hỗ trợ 24/7</h3>
                        <p>
                            Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn mọi lúc.
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
            {{-- ... --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="productCarousel owl-carousel">
                        @foreach ($products as $product)
                            <div class="productItem01">
                                <div class="pi01Thumb">
                                    <img class="main-img"
                                        src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                        alt="{{ $product->name }}">
                                    <img class="hover-img"
                                        src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                        alt="{{ $product->name }}">
                                    <div class="pi01Actions">
                                        <a href="#" class="pi01Cart"><i class="fa-solid fa-shopping-cart"></i></a>
                                        <a href="#" class="pi01QuickView"><i
                                                class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                        <a href="#" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                    </div>
                                </div>
                                <div class="pi01Details">
                                    <h3 class="product-name"><a
                                            href="{{ route('client.shop.show', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="pi01Price"><ins
                                            class="product-price">{{ number_format($product->price ?? 0) }} VNĐ</ins></div>

                                    <div class="d-flex flex-column mt-2 gap-2">
                                        {{-- Hiển thị MÀU SẮC + THUỘC TÍNH trên cùng dòng --}}
                                        <div class="variant-row">
                                            {{-- Màu sắc --}}
                                            @if (!empty($product->colorData))
                                                <div class="d-flex align-items-center gap-2">
                                                    @foreach ($product->colorData as $color)
                                                        <span class="color-picker" data-image="{{ asset($color['image']) }}"
                                                            data-name="{{ $color['variant_name'] }}"
                                                            data-price="{{ number_format($color['price']) }} VNĐ"
                                                            style="background-color: {{ $color['hex'] }};"
                                                            title="{{ ucfirst($color['name']) }}">
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Các thuộc tính khác --}}
                                            <div class="variant-right">
                                                @foreach ($product->attributesGroup as $name => $values)
                                                    @if ($name != 'Màu sắc')
                                                        @foreach ($values as $value)
                                                            <span class="attribute-item">{{ $value }}</span>
                                                        @endforeach
                                                    @endif
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
    </section>
    <!-- END: Latest Arrival Section -->


    <!-- BEGIN: Deal Product Section -->
    <section class="dealProductSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="dealProductContent">
                        <h2>Enjoy Your Youth!</h2>
                        <p>Không chỉ là thời trang, CHINISHOP còn là “phòng thí nghiệm” của tuổi trẻ - nơi nghiên cứu và cho
                            ra đời nguồn năng lượng mang tên “Youth”. Chúng mình luôn muốn tạo nên những trải nghiệm vui vẻ,
                            năng động và trẻ trung. </p>
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


    <!-- BEGIN: Blog Section -->
    <section class="blogSection">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="secTitle">Tin Tức</h2>
                </div>
                <div class="col-md-6 text-end pdt34">
                    <a href="{{ route('blog') }}" class="ulinaBTN2"><span>Xem tất cả</span></a>
                </div>
            </div>
            <div class="row masonryGrid mt-3" id="masonryGrid2">


                @foreach ($blogs as $blog)
                    @if ($blog->status == 'published')
                        <div class="col-md-6 col-lg-4 col-xl-3 shafItem">
                            <div class="blogItem02">
                                <div class="bi01Meta clearfix">
                                    <span><i class="fa-solid fa-folder-open"></i>{{ $blog->postCategory->name }}</span>
                                    <span><i class="fa-solid fa-clock"></i>{{ $blog->created_at->format('d/m/Y') }}</span>
                                </div>
                                <h3><a href="{{ route('blog_detail', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a>
                                </h3>
                                <img src="{{ $blog->featured_image }}" alt=""
                                    style="width: 200px; height: 130px; object-fit: cover; border-radius: 8px;">
                                <a href="{{ route('blog_detail', ['slug' => $blog->slug]) }}" class="ulinaLink"><i
                                        class="fa-solid fa-angle-right"></i>Chi tiết</a>
                            </div>
                        </div>
                    @endif
                @endforeach


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
                    <h2 class="secTitle">BRAND HỢP TÁC CHINISHOP</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">

                        <div class="instagramSlider owl-carousel">
                            @foreach ($brands as $brand)
                            <a href="#" class="instagramPhoto">
                                <img src="{{ $brand->brand_image }}" alt="Ulina Instagram" / style="width: 200px; height: 200px;">
                            </a>
                            @endforeach
                        </div>


                </div>
            </div>
        </div>
    </section>
    <!-- END: Instagram Section -->



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
                                        <a href="javascript:void(0);">Fashion</a>, <a href="javascript:void(0);">Bags</a>,
                                        <a href="javascript:void(0);">Girls</a>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.color-picker').forEach(picker => {
                picker.addEventListener('click', function() {
                    const imageUrl = this.dataset.image;
                    const name = this.dataset.name;
                    const price = this.dataset.price;

                    const container = this.closest('.productItem01');

                    const mainImg = container.querySelector('.main-img');
                    const hoverImg = container.querySelector('.hover-img');
                    const nameEl = container.querySelector('.product-name a');
                    const priceEl = container.querySelector('.product-price');

                    if (mainImg && imageUrl) {
                        mainImg.src = imageUrl;
                    }

                    if (hoverImg && imageUrl) {
                        hoverImg.src = imageUrl;
                    }

                    if (nameEl && name) {
                        nameEl.innerText = name;
                    }

                    if (priceEl && price) {
                        priceEl.innerText = price;
                    }

                    // toggle selected class
                    container.querySelectorAll('.color-picker').forEach(el => el.classList.remove(
                        'selected'));
                    this.classList.add('selected');
                });
            });
        });
    </script>
@endsection
