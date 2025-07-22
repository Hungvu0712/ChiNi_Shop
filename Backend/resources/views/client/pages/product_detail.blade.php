@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Shop')
@section('css')
    <style>
        .color-picker.active {
            outline: 2px solid #000;
            outline-offset: 2px;
        }

        .pcvContainer label.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .productGalleryThumb {
            display: flex;
            flex-direction: row;
            justify-content: center;
            /* ✅ Căn giữa thumbnail */
            align-items: center;
            gap: 10px;
            /* ✅ Khoảng cách giữa ảnh */
            flex-wrap: nowrap;
            overflow-x: auto;
            /* ✅ Cuộn ngang nếu nhiều ảnh */
            scrollbar-width: none;
            /* Firefox: ẩn scrollbar */
        }

        /* Chrome: ẩn scrollbar */
        .productGalleryThumb::-webkit-scrollbar {
            display: none;
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
            border-radius: 4px;
            cursor: pointer;
            border: 1px solid #eee;
        }

        .pgtImage.active img {
            border-color: #333;
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

                        <h2 id="product-name">{{ $product->name }}</h2>

                        <div class="pi01Price">
                            <ins id="product-price">{{ number_format((float) $product->price ?? ($product->variants->first()->price ?? 0)) }}
                                VNĐ</ins>
                        </div>

                        <div class="productRadingsStock clearfix">
                            <div class="productRatings float-start">
                                <div class="productRatingWrap">
                                    <div class="star-rating"><span></span></div>
                                </div>
                                <div class="ratingCounts">52 Reviews</div>
                            </div>
                            <div class="productStock float-end">
                                <span>Available :</span> <span
                                    id="product-stock">{{ $product->variants->first()->quantity ?? 0 }}</span>
                            </div>
                        </div>

                        <div class="pcExcerpt">
                            {!! $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' !!}
                        </div>

                        <div class="pcVariations">
                            @foreach ($attributeNames as $attr)
                                <div class="pcVariation">
                                    <span>{{ ucfirst($attr) }}</span>
                                    <div class="pcvContainer">
                                        @foreach ($attributeValues[$attr] as $val)
                                            <div class="pswItem">
                                                <input type="radio" class="variant-picker" name="{{ $attr }}"
                                                    value="{{ $val }}"
                                                    id="{{ $attr }}_{{ $val }}">
                                                <label
                                                    for="{{ $attr }}_{{ $val }}">{{ $val }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
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
                                                <ins id="product-price">{{ number_format($product->price ?? ($product->variants->first()->price ?? 0)) }}
                                                    VNĐ</ins>
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
            const variantsMap = @json($variantsMap);
            const selectedAttributes = {};

            // Lấy các phần tử cần cập nhật
            const mainProductImage = document.getElementById('mainProductImage');
            const productNameEl = document.getElementById('product-name');
            const productPriceEl = document.getElementById('product-price');
            const productSkuEl = document.querySelector('.sku-field');
            const productStockEl = document.getElementById('product-stock');
            const productGalleryThumb = document.querySelector(
                '.productGalleryThumb'); // Container của gallery thumbnail

            // Lưu trữ URL của ảnh sản phẩm chính ban đầu
            const originalProductImageSrc = mainProductImage.src;

            // Lấy tất cả các thumbnail hiện có ban đầu và lưu trữ URL DUY NHẤT của chúng
            // Sử dụng Set để đảm bảo initialThumbnailUrls không chứa URL trùng lặp từ đầu
            const initialThumbnailUrls = Array.from(new Set(Array.from(productGalleryThumb.querySelectorAll(
                '.pgtImage img')).map(img => img.src)));

            // Hàm để cập nhật gallery thumbnails
            function updateGalleryThumbnails(variantImageSrc, isVariantSelected = false) {
                // Xóa thumbnails cũ
                while (productGalleryThumb.firstChild) {
                    productGalleryThumb.removeChild(productGalleryThumb.firstChild);
                }

                const finalOrderedUrls = [];

                if (isVariantSelected && variantImageSrc) {
                    // Nếu chọn biến thể: chỉ hiển thị ảnh biến thể
                    finalOrderedUrls.push(variantImageSrc);
                } else {
                    // Ngược lại: hiển thị toàn bộ ảnh gốc ban đầu
                    initialThumbnailUrls.forEach(url => finalOrderedUrls.push(url));
                }

                finalOrderedUrls.forEach(url => {
                    const div = document.createElement('div');
                    div.classList.add('pgtImage');
                    if (url === variantImageSrc && isVariantSelected) div.classList.add('active');
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = "Product Thumbnail";
                    div.appendChild(img);
                    productGalleryThumb.appendChild(div);
                });

            }



            // Lắng nghe sự kiện thay đổi của các biến thể
            document.querySelectorAll('.variant-picker').forEach(picker => {
                picker.addEventListener('change', function() {
                    const attr = this.name;
                    const value = this.value;
                    selectedAttributes[attr] = value;

                    const keys = @json($attributeNames);
                    // Sắp xếp lại keys để tạo key khớp với variantsMap
                    const sortedKeys = keys.sort((a, b) => {
                        // Đảm bảo thứ tự thuộc tính khớp với cách bạn tạo key trong controller
                        // Nếu bạn có một thứ tự cụ thể cho các thuộc tính (ví dụ: Màu sắc trước Size),
                        // bạn có thể định nghĩa logic sắp xếp ở đây.
                        return 0;
                    });

                    const key = sortedKeys.map(k => selectedAttributes[k] || '').join('-');
                    const variant = variantsMap[key];

                    if (variant) {
                        // Cập nhật thông tin sản phẩm
                        productNameEl.innerText = variant.name;
                        productPriceEl.innerText = Number(variant.price).toLocaleString() + ' VNĐ';
                        productSkuEl.innerText = variant.sku || 'N/A';
                        productStockEl.innerText = variant.quantity;

                        // Cập nhật ảnh chính
                        mainProductImage.src = variant.variant_image;

                        // ✅ Gọi với cờ "true" để chỉ hiển thị ảnh biến thể
                        updateGalleryThumbnails(variant.variant_image, true);
                    } else {
                        console.log('Không tìm thấy biến thể cho sự kết hợp thuộc tính này.');
                        mainProductImage.src = originalProductImageSrc;

                        // ✅ Gọi lại gallery ảnh gốc
                        updateGalleryThumbnails(originalProductImageSrc, false);
                    }
                });
            });
            productGalleryThumb.addEventListener('click', function(event) {
                const clickedPgtImage = event.target.closest('.pgtImage');
                if (clickedPgtImage && clickedPgtImage.querySelector('img')) {
                    mainProductImage.src = clickedPgtImage.querySelector('img').src;
                    // Xóa class 'active' khỏi tất cả và thêm vào thumbnail được click
                    productGalleryThumb.querySelectorAll('.pgtImage').forEach(div => div.classList.remove(
                        'active'));
                    clickedPgtImage.classList.add('active');
                }
            });

            updateGalleryThumbnails(mainProductImage.src);
        });
    </script>
@endsection
