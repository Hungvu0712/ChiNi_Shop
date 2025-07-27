@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Shop')
@section('css')
    <style>
        .color-picker.active {
            outline: 2px solid #000;
            outline-offset: 2px;
        }

        .attribute-item input:checked+span {
            border: 2px solid #000 !important;
            font-weight: bold;
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

                        <div class="pcVariations d-flex flex-column gap-2 mt-3">
                            {{-- ✅ Hiển thị Màu sắc --}}
                            @if (!empty($product->colorData))
                                <div class="pcVariation">
                                    <span>Màu sắc</span>
                                    <div class="pcvContainer d-flex align-items-center gap-2">
                                        @foreach ($product->colorData as $index => $color)
                                            <span class="color-picker"
                                                style="background-color: {{ $color['hex'] }};
           width: 24px; height: 24px; border-radius: 50%;
           border: 1px solid {{ $color['hex'] === '#ffffff' ? '#ccc' : $color['hex'] }};
           cursor: pointer;"
                                                title="{{ ucfirst($color['name']) }}"
                                                data-attribute-name="{{ ucfirst($color['attribute_key']) }}"
                                                data-attribute-key="{{ $color['attribute_key'] }}"
                                                data-value="{{ $color['name'] }}" data-image="{{ $color['image'] }}"
                                                data-name="{{ $color['variant_name'] }}"
                                                data-price="{{ number_format($color['price']) }} VNĐ">
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- ✅ Hiển thị các thuộc tính khác --}}
                            @foreach ($product->attributesGroup as $name => $values)
                                @if (strtolower($name) !== 'màu sắc')
                                    <div class="pcVariation">
                                        <span>{{ ucfirst($name) }}</span>
                                        <div class="pcvContainer d-flex flex-wrap gap-2">
                                            @foreach ($values as $index => $value)
                                                <label class="attribute-item" style="cursor: pointer;">
                                                    <input type="radio" name="{{ ucfirst($name) }}"
                                                        value="{{ $value }}"
                                                        data-variant-id="{{ $value_id ?? '' }}" {{-- hoặc ID tương ứng --}}
                                                        class="variant-picker d-none">
                                                    <span
                                                        class="badge bg-light text-dark px-2 py-1 border">{{ $value }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="pcBtns">
                            <div class="quantity clearfix">
                                <button type="button" class="qtyBtn btnMinus">-</button>
                                <input type="number" class="carqty input-text qty text" name="quantity" value="1">
                                <button type="button" class="qtyBtn btnPlus">+</button>
                            </div>
                            <button type="button" class="ulinaBTN add-to-cart-btn" data-product-id="{{ $product->id }}">
                                <span>Add to Cart</span>
                            </button>
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
                            <button class="active" id="description-tab" data-bs-toggle="tab"
                                data-bs-target="#description" type="button" role="tab" aria-controls="description"
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
                                                <a
                                                    href="{{ route('client.shop.show', $product->slug) }}">{{ $product->name }}</a>
                                            </h3>
                                            <div class="pi01Price">
                                                <ins>{{ number_format($product->price ?? ($product->variants->first()->price ?? 0)) }}
                                                    VNĐ</ins>
                                            </div>

                                            <div
                                                class="pi01Variations d-flex justify-content-between align-items-start flex-wrap">
                                                {{-- Màu sắc bên trái --}}
                                                <div class="pi01VColor d-flex gap-1">
                                                    @foreach ($product->colors ?? [] as $index => $color)
                                                        @php
        $name = is_array($color) ? $color['name'] ?? '' : $color;
        $hex = is_array($color) ? $color['hex'] ?? '#ccc' : '#ccc';
        $border = $hex === '#ffffff' ? '#999' : '#ccc';
        $boxShadow =
            $hex === '#ffffff' ? 'box-shadow: 0 0 2px #999;' : '';
                                                        @endphp
                                                        <div class="pi01VCItem"
                                                            style="background-color: {{ $hex }};
                width: 18px; height: 18px;
                border-radius: 50%;
                border: 1px solid {{ $border }};
                {{ $boxShadow }}; display: inline-block;">
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- Các biến thể còn lại bên phải --}}
                                                <div class="pi01VOther d-flex gap-2 flex-wrap ms-auto">
                                                    @foreach ($product->otherAttributes ?? [] as $attrName => $attrValues)
                                                        @foreach ($attrValues as $value)
                                                            <span
                                                                class="badge bg-light text-dark border px-2 py-1">{{ $value }}</span>
                                                        @endforeach
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
{{-- load toastr --}}
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const variantsMap = @json($variantsMap);
            const attributeNames = @json($attributeNamesReadable);

            const normalize = str => str.trim().toLowerCase();

            const selectedAttributes = {};
            const normalizedToOriginal = {};

            // Khởi tạo selectedAttributes với key đã normalize
            attributeNames.forEach(attr => {
                const norm = normalize(attr);
                normalizedToOriginal[norm] = attr;
                selectedAttributes[norm] = '';
            });

            const mainProductImage = document.getElementById('mainProductImage');
            const productNameEl = document.getElementById('product-name');
            const productPriceEl = document.getElementById('product-price');
            const productSkuEl = document.querySelector('.sku-field');
            const productStockEl = document.getElementById('product-stock');
            const productGalleryThumb = document.querySelector('.productGalleryThumb');
            const originalProductImageSrc = mainProductImage.src;

            const initialThumbnailUrls = Array.from(new Set(
                Array.from(productGalleryThumb.querySelectorAll('.pgtImage img')).map(img => img.src)
            ));

            function updateGalleryThumbnails(variantImageSrc, isVariantSelected = false) {
                while (productGalleryThumb.firstChild) {
                    productGalleryThumb.removeChild(productGalleryThumb.firstChild);
                }

                const finalUrls = [];

                if (isVariantSelected && variantImageSrc) {
                    finalUrls.push(variantImageSrc);
                } else {
                    initialThumbnailUrls.forEach(url => finalUrls.push(url));
                }

                finalUrls.forEach(url => {
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

            function handleVariantChange() {
                if (Object.values(selectedAttributes).some(val => !val)) {
                    console.warn('⚠️ Cần chọn đầy đủ các thuộc tính:', selectedAttributes);
                    return;
                }

                // Tạo key không phụ thuộc thứ tự thuộc tính
                const sortedKey = Object.keys(selectedAttributes)
                    .sort()
                    .map(k => selectedAttributes[k])
                    .join('-');

                const variant = variantsMap[sortedKey];

                console.log("selectedAttributes", selectedAttributes);
                console.log("builtKey", sortedKey);
                console.log("variantsMap keys:", Object.keys(variantsMap));

                if (variant) {
                    productNameEl.innerText = variant.name;
                    productPriceEl.innerText = Number(variant.price).toLocaleString() + ' VNĐ';
                    productSkuEl.innerText = variant.sku || 'N/A';
                    productStockEl.innerText = variant.quantity;
                    mainProductImage.src = variant.variant_image;
                    updateGalleryThumbnails(variant.variant_image, true);

                    const addToCartBtn = document.querySelector('.add-to-cart-btn');
                    if (addToCartBtn) {
                        addToCartBtn.dataset.variantId = variant.id;
                    }
                } else {
                    console.warn('❌ Không tìm thấy biến thể:', sortedKey);
                    mainProductImage.src = originalProductImageSrc;
                    updateGalleryThumbnails(originalProductImageSrc, false);
                }
            }

            // Radio buttons
            document.querySelectorAll('.variant-picker').forEach(picker => {
                picker.addEventListener('change', function() {
                    const normKey = normalize(this.name);
                    selectedAttributes[normKey] = this.value;
                    handleVariantChange();
                });
            });

            // Color picker buttons
            document.querySelectorAll('.color-picker').forEach(picker => {
                picker.addEventListener('click', function() {
                    const attrName = this.dataset.attributeName;
                    const normKey = normalize(attrName);
                    const value = this.dataset.value;

                    selectedAttributes[normKey] = value;

                    document.querySelectorAll('.color-picker').forEach(p => p.classList.remove(
                        'active'));
                    this.classList.add('active');

                    handleVariantChange();
                });
            });

            // Click vào ảnh thumbnail
            productGalleryThumb.addEventListener('click', function(event) {
                const clicked = event.target.closest('.pgtImage');
                if (clicked && clicked.querySelector('img')) {
                    mainProductImage.src = clicked.querySelector('img').src;
                    productGalleryThumb.querySelectorAll('.pgtImage').forEach(div => div.classList.remove(
                        'active'));
                    clicked.classList.add('active');
                }
            });

            // Tăng giảm số lượng
            const btnMinus = document.querySelector('.btnMinus');
            const btnPlus = document.querySelector('.btnPlus');
            const qtyInput = document.querySelector('input[name="quantity"]');

            if (btnMinus && btnPlus && qtyInput) {
                btnMinus.addEventListener('click', () => {
                    let current = parseInt(qtyInput.value) || 1;
                    if (current > 1) qtyInput.value = current - 1;
                });

                btnPlus.addEventListener('click', () => {
                    let current = parseInt(qtyInput.value) || 1;
                    qtyInput.value = current + 1;
                });
            }

            // Add to Cart
            const addToCartBtn = document.querySelector('.add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const variantId = this.dataset.variantId;
                    const quantity = parseInt(qtyInput.value) || 1;

                    if (!variantId) {
                        alert("Vui lòng chọn đầy đủ biến thể sản phẩm.");
                        return;
                    }

                    console.log("🛒 Add to Cart");
                    console.log("product_id:", productId);
                    console.log("product_variant_id:", variantId);
                    console.log("quantity:", quantity);

                    // fetch('/cart/add', { ... })
                });
            }

            // Gọi cập nhật ảnh ban đầu
            updateGalleryThumbnails(mainProductImage.src);
        });
    </script>

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right", // Hoặc "toast-button-right"
            "timeOut": "3000"
        }
    </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const variantsMap = @json($variantsMap); // Từ controller
                    const attributeNames = @json($attributeNames); // ['Màu sắc', 'Kích cỡ'] v.v.
                    const selectedAttributes = {};

                    // Khởi tạo selectedAttributes rỗng
                    attributeNames.forEach(attr => selectedAttributes[attr] = '');

                    // Các phần tử DOM

                    const mainProductImage = document.getElementById('mainProductImage');
                    const productNameEl = document.getElementById('product-name');
                    const productPriceEl = document.getElementById('product-price');
                    const productSkuEl = document.querySelector('.sku-field');
                    const productStockEl = document.getElementById('product-stock');
                    const productGalleryThumb = document.querySelector('.productGalleryThumb');
                    const originalProductImageSrc = mainProductImage.src;

                    // Ảnh thumbnail gốc ban đầu
                    const initialThumbnailUrls = Array.from(new Set(
                        Array.from(productGalleryThumb.querySelectorAll('.pgtImage img')).map(img => img.src)
                    ));

                    function updateGalleryThumbnails(variantImageSrc, isVariantSelected = false) {
                        while (productGalleryThumb.firstChild) {
                            productGalleryThumb.removeChild(productGalleryThumb.firstChild);
                        }

                        const finalUrls = [];

                        if (isVariantSelected && variantImageSrc) {
                            finalUrls.push(variantImageSrc);
                        } else {
                            initialThumbnailUrls.forEach(url => finalUrls.push(url));
                        }

                        finalUrls.forEach(url => {
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

                    // ✅ Hàm dùng chung để xử lý chọn biến thể
                    function handleVariantChange() {
                        const key = attributeNames.map(attr => selectedAttributes[attr] || '').join('-');
                        const variant = variantsMap[key];

                        if (variant) {

                            productNameEl.innerText = variant.name;
                            productPriceEl.innerText = Number(variant.price).toLocaleString() + ' VNĐ';
                            productSkuEl.innerText = variant.sku || 'N/A';
                            productStockEl.innerText = variant.quantity;
                            mainProductImage.src = variant.variant_image;
                            updateGalleryThumbnails(variant.variant_image, true);

                            // Lưu variant_id vào button để gửi khi Add to Cart
                            const addToCartBtn = document.querySelector('.add-to-cart-btn');
                            if (addToCartBtn) {
                                addToCartBtn.dataset.variantId = variant.id;
                            }
                        } else {
                            console.warn('❌ Không tìm thấy biến thể:', key);
                            mainProductImage.src = originalProductImageSrc;
                            updateGalleryThumbnails(originalProductImageSrc, false);
                        }
                    }

                    // ✅ Sự kiện cho radio buttons
                    document.querySelectorAll('.variant-picker').forEach(picker => {
                        picker.addEventListener('change', function() {
                            selectedAttributes[this.name] = this.value;
                            handleVariantChange();
                        });
                    });

                    // ✅ Sự kiện click cho .color-picker
                    document.querySelectorAll('.color-picker').forEach(picker => {
                        picker.addEventListener('click', function() {
                            const attrName = 'Màu sắc'; // Nếu cần có thể lấy từ `data-attribute`
                            const value = this.dataset.value;

                            // Gán giá trị được chọn
                            selectedAttributes[attrName] = value;

                            // Toggle active class
                            document.querySelectorAll('.color-picker').forEach(p => p.classList.remove(
                                'active'));
                            this.classList.add('active');

                            handleVariantChange();
                        });
                    });

                    // ✅ Click vào thumbnail đổi ảnh
                    productGalleryThumb.addEventListener('click', function(event) {
                        const clicked = event.target.closest('.pgtImage');
                        if (clicked && clicked.querySelector('img')) {
                            mainProductImage.src = clicked.querySelector('img').src;
                            productGalleryThumb.querySelectorAll('.pgtImage').forEach(div => div.classList.remove(
                                'active'));
                            clicked.classList.add('active');
                        }
                    });

                    // ✅ Xử lý số lượng +/-
                    const btnMinus = document.querySelector('.btnMinus');
                    const btnPlus = document.querySelector('.btnPlus');
                    const qtyInput = document.querySelector('input[name="quantity"]');

                    if (btnMinus && btnPlus && qtyInput) {
                        btnMinus.addEventListener('click', () => {
                            let current = parseInt(qtyInput.value) || 1;
                            if (current > 1) qtyInput.value = current - 1;
                        });

                        btnPlus.addEventListener('click', () => {
                            let current = parseInt(qtyInput.value) || 1;
                            qtyInput.value = current + 1;
                        });
                    }

                    // ✅ Xử lý Add to Cart
                    const addToCartBtn = document.querySelector('.add-to-cart-btn');
                   if (addToCartBtn) {
                        addToCartBtn.addEventListener('click', function () {
                            const productId = this.dataset.productId;
                            const variantId = this.dataset.variantId;
                            const quantity = parseInt(qtyInput.value) || 1;

                            if (!variantId) {
                                toastr.warning("Vui lòng chọn đầy đủ biến thể sản phẩm.");
                                return;
                            }

                            fetch("{{ route('cart.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    product_id: productId,
                                    product_variant_id: variantId,
                                    quantity: quantity
                                })
                            })
                                .then(async response => {
                                    const data = await response.json();

                                    if (!response.ok) {
                                        // Khi lỗi như: 422, 400, 500...
                                        toastr.error(data.message || 'Đã có lỗi xảy ra!');
                                        throw new Error(data.message);
                                    }

                                    // Thành công
                                    updateCartCount(data.cart_count);
                                    
                                    toastr.success(data.message || 'Thêm sản phẩm vào giỏ hàng!');
                                })
                                .catch(error => {
                                    console.error('❌ Lỗi khi thêm vào giỏ hàng:', error.message);
                                });
                        });
                    }

                    // ✅ Gọi cập nhật ảnh ban đầu
                    updateGalleryThumbnails(mainProductImage.src);
                });
            </script>


@endsection
