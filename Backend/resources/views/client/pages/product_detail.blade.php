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
        </div>
    </section>
@endsection
@section('script')
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

@endsection
