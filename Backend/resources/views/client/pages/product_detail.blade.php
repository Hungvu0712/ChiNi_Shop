@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Shop')
@section('css')
    <style>
        .color-picker.active {
            outline: 2px solid #000;
            outline-offset: 2px;
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
    <section class="shopDetailsPageSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="productGalleryWrap">
                        <div class="productGallery">
                            <div class="pgImage">
                                <img id="mainProductImage" src="{{ $galleryImages[0] ?? '' }}" alt="{{ $product->name }}" />
                            </div>
                        </div>

                        <div class="productGalleryThumbWrap">
                            <div class="productGalleryThumb">
                                @foreach ($galleryImages as $image)
                                    <div class="pgtImage">
                                        <img src="{{ $image }}" alt="{{ $product->name }}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="productContent">
                        <div class="pcCategory">
                            <a href="#">{{ $product->category->name ?? 'Uncategorized' }}</a>
                        </div>
                        <h2>{{ $product->name }}</h2>
                        <div class="pi01Price">
                            <ins>{{ number_format($product->variants->first()->price ?? 0) }} VNĐ</ins>
                        </div>
                        <div class="productRadingsStock clearfix">
                            <div class="productRatings float-start">
                                <div class="productRatingWrap">
                                    <div class="star-rating"><span></span></div>
                                </div>
                                <div class="ratingCounts">52 Reviews</div>
                            </div>
                            <div class="productStock float-end">
                                <span>Available :</span> {{ $product->variants->first()->quantity ?? 0 }}
                            </div>
                        </div>
                        <div class="pcExcerpt">
                            {!! $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' !!}
                        </div>
                        <div class="pcVariations">
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

                            <div class="pcVariation">
                                <span>Color</span>
                                <div class="pcvContainer d-flex gap-1">
                                    @foreach ($product->colors ?? [] as $colorKey)
                                        @php
                                            $hex = $colorMap[$colorKey] ?? '#ccc';
                                            $border = $hex === '#ffffff' ? '#999' : '#ccc';
                                            $boxShadow = $hex === '#ffffff' ? 'box-shadow: 0 0 2px #999;' : '';
                                        @endphp
                                        <span class="color-picker"
                                            style="background-color: {{ $hex }};
                       width: 18px;
                       height: 18px;
                       border-radius: 50%;
                       border: 1px solid {{ $border }};
                       {{ $boxShadow }};
                       display: inline-block;">
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pcVariation pcv2">
                                <span>Size</span>
                                <div class="pcvContainer">
                                    @foreach ($product->sizes as $size)
                                        <div class="pswItem">
                                            <input type="radio" name="size" value="{{ $size }}"
                                                id="size_{{ $size }}">
                                            <label for="size_{{ $size }}">{{ $size }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="pcBtns">
                            <div class="quantity clearfix">
                                <button type="button" class="qtyBtn btnMinus">-</button>
                                <input type="number" class="carqty input-text qty text" name="quantity" value="1">
                                <button type="button" class="qtyBtn btnPlus">+</button>
                            </div>
                            <button type="submit" class="ulinaBTN"><span>Add to Cart</span></button>
                            <a href="#" class="pcWishlist"><i class="fa-solid fa-heart"></i></a>
                            <a href="#" class="pcCompare"><i class="fa-solid fa-right-left"></i></a>
                        </div>
                        <div class="pcMeta">
                            <p><span>Sku</span> <a href="#">{{ $product->sku ?? 'N/A' }}</a></p>
                            <p class="pcmTags">
                                <span>Tags:</span>
                                @foreach (explode(',', $product->tags ?? '') as $tag)
                                    <a href="javascript:void(0);">{{ trim($tag) }}</a>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </p>
                            <p class="pcmSocial">
                                <span>Share</span>
                                <a class="fac" href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                <a class="twi" href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a class="lin" href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a class="ins" href="#"><i class="fa-brands fa-instagram"></i></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row productTabRow">
                <div class="col-lg-12">
                    <ul class="nav productDetailsTab" id="productDetailsTab" role="tablist">
                        <li role="presentation">
                            <button class="active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description"
                                type="button" role="tab" aria-controls="description"
                                aria-selected="true">Description</button>
                        </li>
                        <li role="presentation">
                            <button id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button"
                                role="tab" aria-controls="reviews" aria-selected="false" tabindex="-1">Item
                                Review</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="desInfoRev_content">
                        <div class="tab-pane fade show active" id="description" role="tabpanel"
                            aria-labelledby="description-tab" tabindex="0">
                            <div class="productDescContentArea">
                                <div class="row">
                                    {{-- Mô tả --}}
                                    <div class="col-lg-6">
                                        <div class="descriptionContent">
                                            <h3>Product Details</h3>
                                            <p>{!! $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' !!}</p>
                                        </div>
                                    </div>

                                    {{-- Thông tin phụ --}}
                                    <div class="col-lg-6">
                                        <div class="descriptionContent featureCols">
                                            <h3>Additional Information</h3>
                                            <ul>
                                                <li><strong>Brand:</strong> {{ $product->brand->name ?? 'Không rõ' }}</li>
                                                <li><strong>Weight:</strong> {{ $product->weight ?? 'Đang cập nhật' }}g
                                                </li>
                                                {{-- Nếu có thêm thông tin, bạn có thể nối thêm ở đây --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab"
                            tabindex="0">
                            <div class="productReviewArea">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3>10 Reviews</h3>
                                        <div class="reviewList">
                                            <ol>
                                                <li>
                                                    <div class="postReview">
                                                        <img src="images/author/7.jpg" alt="Post Review">
                                                        <h2>Greaet product. Packaging was also good!</h2>
                                                        <div class="postReviewContent">
                                                            Desectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                                            labore et dolore ma na alihote pare ei gansh es gan quim veniam,
                                                            quis nostr udg exercitation ullamco laboris nisi ut aliquip
                                                        </div>
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="reviewMeta">
                                                            <h4>John Manna</h4>
                                                            <span>on June 10, 2022</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="postReview">
                                                        <img src="images/author/8.jpg" alt="Post Review">
                                                        <h2>The item is very comfortable and soft!</h2>
                                                        <div class="postReviewContent">
                                                            Desectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                                            labore et dolore ma na alihote pare ei gansh es gan quim veniam,
                                                            quis nostr udg exercitation ullamco laboris nisi ut aliquip
                                                        </div>
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="reviewMeta">
                                                            <h4>Robert Thomas</h4>
                                                            <span>on June 10, 2022</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="postReview">
                                                        <img src="images/author/9.jpg" alt="Post Review">
                                                        <h2>I liked the product, it is awesome.</h2>
                                                        <div class="postReviewContent">
                                                            Desectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                                            labore et dolore ma na alihote pare ei gansh es gan quim veniam,
                                                            quis nostr udg exercitation ullamco laboris nisi ut aliquip
                                                        </div>
                                                        <div class="productRatingWrap">
                                                            <div class="star-rating"><span></span></div>
                                                        </div>
                                                        <div class="reviewMeta">
                                                            <h4>Ken Williams</h4>
                                                            <span>on June 10, 2022</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="commentFormArea">
                                            <h3>Add A Review</h3>
                                            <div class="reviewFrom">
                                                <form method="post" action="#" class="row">
                                                    <div class="col-lg-12">
                                                        <div class="reviewStar">
                                                            <label>Your Rating</label>
                                                            <div class="rsStars"><i class="fa-regular fa-star"></i><i
                                                                    class="fa-regular fa-star"></i><i
                                                                    class="fa-regular fa-star"></i><i
                                                                    class="fa-regular fa-star"></i><i
                                                                    class="fa-regular fa-star"></i></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <input type="text" name="comTitle" placeholder="Review title">
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <textarea name="comComment" placeholder="Write your review here"></textarea>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" name="comName" placeholder="Your name">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="email" name="comEmail" placeholder="Your email">
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <button type="submit" name="reviewtSubmit"
                                                            class="ulinaBTN"><span>Submit Now</span></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row relatedProductRow">
                <div class="col-lg-12">
                    <h2 class="secTitle">More Products Like This</h2>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="productCarousel owl-carousel">
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

                                @foreach ($relatedProducts as $product)
                                    <div class="productItem01">
                                        <div class="pi01Thumb text-center">
                                            <img src="{{ $product->product_image }}" alt="{{ $product->name }}"
                                                style="max-height: 280px; object-fit: cover;" />
                                            <img src="{{ $product->product_image }}" alt="{{ $product->name }}"
                                                style="max-height: 280px; object-fit: cover;" />
                                            <div class="pi01Actions">
                                                <a href="#" class="pi01Cart"><i
                                                        class="fa-solid fa-shopping-cart"></i></a>
                                                <a href="#" class="pi01QuickView"><i
                                                        class="fa-solid fa-arrows-up-down-left-right"></i></a>
                                                <a href="#" class="pi01Wishlist"><i
                                                        class="fa-solid fa-heart"></i></a>
                                            </div>
                                            <div class="productLabels clearfix">
                                                <span class="plSale">{{ $product->brand->name ?? '' }}</span>
                                            </div>
                                        </div>

                                        <div class="pi01Details">
                                            <h3>
                                                <a href="{{ route('client.shop.show', $product->slug) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </h3>
                                            <div class="pi01Price">
                                                <ins>{{ number_format($product->price, 0, ',', '.') }}đ</ins>
                                            </div>

                                            <div class="pi01Variations">
                                                {{-- Màu sắc --}}
                                                <div class="pi01VColor d-flex gap-1">
                                                    @foreach ($product->colors ?? [] as $index => $colorKey)
                                                        @php
                                                            $hex = $colorMap[strtolower($colorKey)] ?? '#ccc';
                                                            $border = $hex === '#ffffff' ? '#999' : '#ccc';
                                                            $boxShadow =
                                                                $hex === '#ffffff' ? 'box-shadow: 0 0 2px #999;' : '';
                                                        @endphp
                                                        <div class="pi01VCItem"
                                                            style="background-color: {{ $hex }};
                            width: 18px; height: 18px;
                            border-radius: 50%;
                            border: 1px solid {{ $border }};
                            {{ $boxShadow }};
                            display: inline-block;">
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- Kích thước --}}
                                                <div class="pi01VSize">
                                                    @foreach ($product->sizes ?? [] as $index => $sizeValue)
                                                        <div class="pi01VSItem">
                                                            <input type="radio" name="size_{{ $product->id }}"
                                                                id="size_{{ $product->id }}_{{ $index }}">
                                                            <label
                                                                for="size_{{ $product->id }}_{{ $index }}">{{ $sizeValue }}</label>
                                                        </div>
                                                    @endforeach
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

        </div>
    </section>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.color-picker').forEach(picker => {
                picker.addEventListener('click', function() {
                    const imageUrl = this.getAttribute('data-image');
                    const container = this.closest('.productItem01');
                    const imageElement = container.querySelector('.pi01Thumb img:nth-child(1)');

                    if (imageElement && imageUrl) {
                        imageElement.src = imageUrl;
                    }

                    // hiệu ứng chọn
                    container.querySelectorAll('.color-picker').forEach(el => el.classList.remove(
                        'active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('mainProductImage');

            document.querySelectorAll('.pgtImage img').forEach((thumb) => {
                thumb.addEventListener('click', function() {
                    if (mainImage && this.src) {
                        mainImage.src = this.src;
                    }
                });
            });
        });
    </script>


@endsection
