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


                    <h2 id="product-name">{{ $product->name }}</h2>

                    <div class="pi01Price">
                        <ins id="product-price">{{ number_format((float) $product->price ??
                            ($product->variants->first()->price ?? 0)) }}
                            VNĐ</ins>
                    </div>

                    <div class="productRadingsStock clearfix">
                        <div class="productRatings float-start">

                        </div>
                        <div class="productStock float-end">
                            <span>Số lượng :</span> <span id="product-stock">{{ $product->variants->first()->quantity ??
                                0 }}</span>
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
                                <span class="color-picker" style="background-color: {{ $color['hex'] }};
                                             width: 24px; height: 24px; border-radius: 50%;
                                             border: 1px solid {{ $color['hex'] === '#ffffff' ? '#ccc' : $color['hex'] }};
                                             cursor: pointer;" title="{{ ucfirst($color['name']) }}"
                                    data-attribute-name="Màu sắc" data-value="{{ $color['name'] }}"
                                    data-image="{{ $color['image'] }}" data-name="{{ $color['variant_name'] }}"
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
                                    <input type="radio" name="{{ $name }}" value="{{ $value }}"
                                        data-variant-id="{{ $value_id ?? '' }}" {{-- hoặc ID tương ứng --}}
                                        class="variant-picker d-none">
                                    <span class="badge bg-light text-dark px-2 py-1 border">{{ $value }}</span>
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
                            <span>Thêm giỏ hàng</span>
                        </button>

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
                        <p class="pcCategory">
                            <span>Category: </span><a href="#">{{ $product->category->name ?? 'Uncategorized' }}</a>
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
                            type="button" role="tab" aria-controls="description" aria-selected="true">Mô Tả Sản
                            Phẩm</button>
                    </li>
                    <li role="presentation">
                        <button id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab"
                            aria-controls="reviews" aria-selected="false" tabindex="-1">Lượt Đánh Giá
                        </button>
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
                                        <h3>Mô tả sản phẩm</h3>
                                        <p>{!! $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' !!}
                                        </p>
                                    </div>
                                </div>

                                {{-- Thông tin phụ --}}
                                <div class="col-lg-6">
                                    <div class="descriptionContent featureCols">
                                        <h3>Thông tin sản phẩm</h3>
                                        <ul>
                                            <li><strong>Brand:</strong> {{ $product->brand->name ?? 'Không rõ' }}</li>
                                            <li><strong>Trọng Lượng:</strong> {{ $product->weight ?? 'Đang cập nhật' }}g
                                            </li>
                                            {{-- Nếu có thêm thông tin, bạn có thể nối thêm ở đây --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                        <div class="productReviewArea">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h3 class="mb-0">{{ $reviewCount }} đánh giá</h3>
                                        @if($reviewCount > 0)
                                        @php
                                        $averageRating = $reviews->avg('rating') ?? 0;
                                        @endphp
                                        <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1">
                                            <span class="fw-bold text-primary me-2">{{ number_format($averageRating, 1)
                                                }}/5</span>
                                            <div class="productRatingWrap">
                                                @for ($i = 1; $i <= 5; $i++) <i
                                                    class="{{ $i <= round($averageRating) ? 'fas' : 'far' }} fa-star text-warning small">
                                                    </i>
                                                    @endfor
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="reviewList">
                                        @forelse ($reviews as $review)
                                        <div class="review-item card border-0 shadow-sm mb-4">
                                            <div class="card-body">
                                                <div class="d-flex gap-3">
                                                    <!-- Avatar -->
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ $review->user->profile->avatar ?? asset('images/author/default.jpg') }}"
                                                            alt="{{ $review->user->name ?? 'Ẩn danh' }}"
                                                            class="rounded-circle" width="60" height="60"
                                                            style="object-fit: cover;">
                                                    </div>

                                                    <!-- Nội dung -->
                                                    <div class="flex-grow-1">
                                                        <div
                                                            class="d-flex justify-content-between align-items-start mb-2">
                                                            <h5 class="mb-0">{{ $review->user->name ?? 'Ẩn danh' }}</h5>
                                                            <small class="text-muted">{{
                                                                $review->created_at->format('d/m/Y H:i') }}</small>
                                                        </div>

                                                        <!-- Số sao -->
                                                        <div class="productRatingWrap mb-2">
                                                            @for ($i = 1; $i <= 5; $i++) <i
                                                                class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-warning">
                                                                </i>
                                                                @endfor
                                                        </div>

                                                        <!-- Nội dung đánh giá -->
                                                        <p class="mb-3 text-dark">{{ $review->review }}</p>

                                                        <!-- Hình ảnh đính kèm -->
                                                        @if ($review->images && $review->images->count())
                                                        <div class="review-images d-flex flex-wrap gap-2 mt-2">
                                                            @foreach ($review->images as $image)
                                                            <a href="{{ $image->image_url }}"
                                                                data-fancybox="review-gallery-{{ $review->id }}"
                                                                data-caption="Ảnh đánh giá">
                                                                <img src="{{ $image->image_url }}" alt="Review image"
                                                                    class="rounded" width="80" height="80"
                                                                    style="object-fit: cover; transition: transform 0.2s;">
                                                            </a>
                                                            @endforeach
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="text-center py-5 bg-light rounded-3">
                                            <i class="far fa-comment-alt fs-1 text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Chưa có đánh giá nào cho sản phẩm này.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="commentFormArea">
                                        <h3>Thêm Đánh Giá</h3>
                                        <div class="reviewFrom">
                                            <form method="POST" action="{{ route('client.shop.review') }}"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                <div class="mb-3 rating-stars">
                                                    <label class="form-label">sao</label><br>
                                                    @for ($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" id="star{{ $i }}"
                                                        value="{{ $i }}" />
                                                    <label for="star{{ $i }}"><i class="fa fa-star"></i></label>
                                                    @endfor
                                                </div>


                                                <div class="mb-3">
                                                    <label class="form-label">Mô tả</label>
                                                    <textarea name="review" class="form-control" rows="4"
                                                        required></textarea>

                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Hình Ảnh</label>
                                                    <input type="file" name="comAttachments[]" class="form-control"
                                                        multiple accept="image/*">
                                                </div>

                                                <div class="text-center">
                                                    <button class="btn btn-primary rounded-pill px-4">Thêm
                                                    </button>
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
                <h2 class="secTitle">Sản Phẩm Liên Quan</h2>
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

                                    <div class="productLabels clearfix">
                                        <span class="plSale">{{ $product->brand->name ?? '' }}</span>
                                    </div>
                                </div>

                                <div class="pi01Details">
                                    <h3>
                                        <a href="{{ route('client.shop.show', $product->slug) }}">{{ $product->name
                                            }}</a>
                                    </h3>
                                    <div class="pi01Price">
                                        <ins>{{ number_format($product->price ?? ($product->variants->first()->price ??
                                            0)) }}
                                            VNĐ</ins>
                                    </div>

                                    <div class="pi01Variations d-flex align-items-center w-100">
                                        {{-- SIZE bên trái --}}
                                        <div class="pi01VSize d-flex gap-2 me-auto">
                                            @foreach ($product->sizes ?? [] as $size)
                                            <span class="badge bg-light text-dark border px-2 py-1">{{ $size }}</span>
                                            @endforeach
                                        </div>

                                        {{-- MÀU + biến thể khác bên phải --}}
                                        <div class="pi01VRight d-flex gap-3 align-items-center ms-auto">
                                            {{-- Màu --}}
                                            @if(!empty($product->colors))
                                            <div class="pi01VColor d-flex gap-1">
                                                @foreach ($product->colors as $color)
                                                @php
                                                $hex = is_array($color) ? ($color['hex'] ?? '#ccc') : '#ccc';
                                                $border = $hex === '#ffffff' ? '#999' : '#ccc';
                                                $shadow = $hex === '#ffffff' ? 'box-shadow:0 0 2px #999;' : '';
                                                @endphp
                                                <span class="d-inline-block rounded-circle" style="background-color:{{ $hex }};
                                 width:14px; height:14px;
                                 border:1px solid {{ $border }};
                                 {{ $shadow }}">
                                                </span>
                                                @endforeach
                                            </div>
                                            @endif

                                            {{-- Biến thể khác --}}
                                            @if(!empty($product->otherAttributes))
                                            <div class="pi01VOther d-flex gap-2 flex-wrap">
                                                @foreach ($product->otherAttributes as $attrValues)
                                                @foreach ($attrValues as $value)
                                                <span class="badge bg-light text-dark border px-2 py-1">{{ $value
                                                    }}</span>
                                                @endforeach
                                                @endforeach
                                            </div>
                                            @endif
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
    toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right", // Hoặc "toast-button-right"
            "timeOut": "3000"
        }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                picker.addEventListener('change', function () {
                    selectedAttributes[this.name] = this.value;
                    handleVariantChange();
                });
            });

            // ✅ Sự kiện click cho .color-picker
            document.querySelectorAll('.color-picker').forEach(picker => {
                picker.addEventListener('click', function () {
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
            productGalleryThumb.addEventListener('click', function (event) {
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
                        toastr.warning("Sản phẩm hiện không có cặp biến thể này! Vui lòng chọn lại cặp biến thể khác!");
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
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
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