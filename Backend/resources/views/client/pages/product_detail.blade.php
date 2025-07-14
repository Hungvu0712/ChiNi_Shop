@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Shop')
@section('css')
    <style>
        .color-picker.active {
            outline: 2px solid #000;
            outline-offset: 2px;
        }

        .productGalleryThumb {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .pgtImage {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .pgtImage img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        input[type="file"] {
            height: 45px;
            line-height: 45px;
            padding-left: 15px;
        }

        .rating-stars {
            direction: rtl;
            /* để đảo thứ tự ngôi sao */
            display: inline-flex;
        }

        .rating-stars input[type="radio"] {
            display: none;
        }

        .rating-stars label {
            cursor: pointer;
            font-size: 24px;
            color: #ddd;
            /* Màu sao chưa chọn */
            margin: 0 2px;
        }

        .rating-stars input[type="radio"]:checked~label i {
            color: #ffc107;
            /* Màu sao đã chọn */
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
                        {{-- Danh mục --}}
                        <div class="pcCategory">
                            <a href="#">{{ $product->category->name ?? 'Uncategorized' }}</a>
                        </div>

                        {{-- Tên biến thể: ban đầu là tên SP gốc, sau sẽ thay bằng JS --}}
                        <h2>{{ $product->name }}</h2>

                        {{-- Giá: ban đầu lấy giá variant đầu tiên, sau thay bằng JS --}}
                        <div class="pi01Price">
                            <ins>{{ number_format($product->variants->first()->price ?? 0) }} VNĐ</ins>
                        </div>

                        {{-- Đánh giá & tồn kho --}}
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

                        {{-- Mô tả --}}
                        <div class="pcExcerpt">
                            {!! $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' !!}
                        </div>

                        {{-- Chọn màu & size --}}
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

                            {{-- Màu --}}
                            <div class="pcVariation">
                                <span>Color</span>
                                <div class="pcvContainer d-flex gap-1">
                                    @foreach ($product->variantByColor as $colorKey => $variantData)
                                        @php
                                            $hex = $colorMap[$colorKey] ?? '#ccc';
                                            $border = $hex === '#ffffff' ? '#999' : '#ccc';
                                            $boxShadow = $hex === '#ffffff' ? 'box-shadow: 0 0 2px #999;' : '';
                                        @endphp
                                        <span class="color-picker" data-color="{{ $colorKey }}"
                                            data-variants='@json($variantData['variants'])'
                                            style="background-color: {{ $hex }};
                            width: 18px; height: 18px; border-radius: 50%;
                            border: 1px solid {{ $border }};
                            {{ $boxShadow }};
                            display: inline-block;">
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Size --}}
                            <div class="pcVariation pcv2">
                                <span>Size</span>
                                <div class="pcvContainer">
                                    @foreach ($product->sizes as $size)
                                        <div class="pswItem">
                                            <input type="radio" class="size-picker" name="size"
                                                value="{{ $size }}" id="size_{{ $size }}">
                                            <label for="size_{{ $size }}">{{ $size }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Quantity, Add to cart, Wishlist --}}
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

                        {{-- SKU & Tags --}}
                        <div class="pcMeta">
                            <p><span>Sku</span> <a class="sku-field" href="#">{{ $product->sku ?? 'N/A' }}</a></p>
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
                                        <h3>{{ $reviewCount }} đánh giá</h3>

                                        <div class="reviewList">
                                            <ol>
                                                @forelse ($reviews as $review)
                                                    <li>
                                                        <div class="postReview d-flex gap-3 mb-4">
                                                            {{-- Avatar --}}
                                                            <div>
                                                                <img src="{{ $review->user->avatar ?? asset('images/author/default.jpg') }}"
                                                                    alt="{{ $review->user->name ?? 'Ẩn danh' }}"
                                                                    class="rounded-circle" width="60" height="60"
                                                                    style="object-fit: cover;">
                                                            </div>

                                                            {{-- Nội dung --}}
                                                            <div class="flex-grow-1">
                                                                <div class="mb-1">
                                                                    <h5 class="mb-1">
                                                                        {{ $review->user->name ?? 'Ẩn danh' }}</h5>

                                                                </div>


                                                                {{-- Số sao --}}

                                                                <div class="productRatingWrap mb-2">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i
                                                                            class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-warning"></i>
                                                                    @endfor
                                                                </div>

                                                                {{-- Nội dung đánh giá --}}

                                                                <div class="mb-1">
                                                                    <h5 class="mb-1">{{ $review->review }}</h5>
                                                                    <small
                                                                        class="text-muted d-block">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                                                </div>


                                                                {{-- Hình ảnh đính kèm --}}

                                                                @if ($review->images && $review->images->count())
                                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                                        @foreach ($review->images as $image)
                                                                            <a href="{{ $image->image_url }}"
                                                                                target="_blank">
                                                                                <img src="{{ $image->image_url }}"
                                                                                    alt="Review image"
                                                                                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                                                            </a>
                                                                        @endforeach
                                                                    </div>
                                                                @endif


                                                            </div>
                                                        </div>
                                                    </li>
                                                @empty
                                                    <li>
                                                        <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                                                    </li>
                                                @endforelse
                                            </ol>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="commentFormArea">
                                            <h3>Add A Review</h3>
                                            <div class="reviewFrom">
                                                <form method="POST" action="{{ route('client.shop.review') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf

                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                    <div class="mb-3 rating-stars">
                                                        <label class="form-label">Rating</label><br>
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <input type="radio" name="rating"
                                                                id="star{{ $i }}"
                                                                value="{{ $i }}" />
                                                            <label for="star{{ $i }}"><i
                                                                    class="fa fa-star"></i></label>
                                                        @endfor
                                                    </div>


                                                    <div class="mb-3">
                                                        <label class="form-label">Your Review</label>
                                                        <textarea name="review" class="form-control" rows="4" required></textarea>

                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Upload Images</label>
                                                        <input type="file" name="comAttachments[]"
                                                            class="form-control" multiple accept="image/*">
                                                    </div>

                                                    <div class="text-center">
                                                        <button class="btn btn-primary rounded-pill px-4">Submit
                                                            Review</button>
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
            let selectedColor = null;
            let selectedVariants = null;
            let selectedSize = null;

            // Bấm màu
            document.querySelectorAll('.color-picker').forEach(picker => {
                picker.addEventListener('click', function() {
                    selectedColor = this.getAttribute('data-color');
                    selectedVariants = JSON.parse(this.getAttribute('data-variants') || '{}');
                    updateProductDisplay();
                });
            });

            // Bấm size
            document.querySelectorAll('.size-picker').forEach(sizeInput => {
                sizeInput.addEventListener('change', function() {
                    selectedSize = this.value;
                    updateProductDisplay();
                });
            });

            function updateProductDisplay() {
                if (selectedVariants && selectedSize) {
                    const variant = selectedVariants[selectedSize];

                    if (variant) {
                        const imageUrl = variant.variant_image;
                        const name = variant.variant_name;
                        const price = Number(variant.price).toLocaleString() + ' VNĐ';
                        const sku = variant.sku;
                        const gallery = variant.gallery || [];

                        // Đổi ảnh chính — có fallback
                        const mainImage = document.getElementById('mainProductImage');
                        if (mainImage) {
                            if (imageUrl && imageUrl !== 'null' && imageUrl !== '') {
                                mainImage.src = imageUrl;
                            } else {
                                // fallback: gán lại ảnh gốc
                                mainImage.src = '{{ $galleryImages[0] ?? '' }}';
                            }
                        }

                        // Đổi bộ ảnh nhỏ nếu gallery con có
                        const thumbContainer = document.querySelector('.productGalleryThumb');
                        if (thumbContainer) {
                            if (gallery.length) {
                                thumbContainer.innerHTML = gallery.map(img =>
                                    `<div class="pgtImage"><img src="${img}" alt=""></div>`
                                ).join('');
                            } else {
                                // fallback: giữ nguyên hoặc reload lại gallery gốc
                                thumbContainer.innerHTML = `{!! collect($galleryImages)->map(fn($img) => '<div class="pgtImage"><img src="' . $img . '" alt=""></div>')->implode('') !!}`;
                            }
                        }

                        // Tên biến thể
                        if (name) {
                            const titleEl = document.querySelector('.productContent h2');
                            if (titleEl) titleEl.innerText = name;
                        }

                        // Giá
                        if (price) {
                            const priceEl = document.querySelector('.pi01Price ins');
                            if (priceEl) priceEl.innerText = price;
                        }

                        // SKU
                        if (sku) {
                            const skuEl = document.querySelector('.sku-field');
                            if (skuEl) skuEl.innerText = sku;
                        }
                    } else {
                        console.warn(`❗ Không tìm thấy biến thể size: ${selectedSize}`);
                    }
                }
            }

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const starInputs = document.querySelectorAll('.rating-stars input');
            const starLabels = document.querySelectorAll('.rating-stars label');

            starInputs.forEach((input, index) => {
                input.addEventListener('change', function() {
                    const value = parseInt(this.value);

                    starLabels.forEach((label, i) => {
                        label.classList.toggle('checked', 5 - i < value + 1);
                    });
                });
            });
        });
    </script>


@endsection
