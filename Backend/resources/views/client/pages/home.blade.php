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
                    <div class="productItem01 card h-100 border-0 shadow-sm">
                        <div class="pi01Thumb ratio ratio-1x1 position-relative overflow-hidden">
                            <img class="main-img img-fluid w-100 h-100 object-fit-cover position-absolute top-0 start-0"
                                src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                alt="{{ $product->name }}">
                            <img class="hover-img img-fluid w-100 h-100 object-fit-cover position-absolute top-0 start-0"
                                src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                                alt="{{ $product->name }}" style="opacity: 0; transition: opacity 0.3s ease;">
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
                                    <div class="color-options d-flex flex-wrap gap-2 align-items-center mb-2 mb-sm-0">
                                        @foreach ($product->colorData as $color)
                                        <span class="color-picker rounded-circle border border-light shadow-sm"
                                            style="width: 24px; height: 24px; cursor: pointer; background-color: {{ $color['hex'] }};"
                                            data-image="{{ asset($color['image']) }}"
                                            data-name="{{ $color['variant_name'] }}"
                                            data-price="{{ number_format($color['price']) }} VNĐ"
                                            title="{{ ucfirst($color['name']) }}" data-bs-toggle="tooltip">
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif

                                    {{-- Các thuộc tính khác --}}
                                    <div class="attribute-options d-flex flex-wrap gap-2">
                                        @foreach ($product->attributesGroup as $name => $values)
                                        @if ($name != 'Màu sắc')
                                        @foreach ($values as $value)
                                        <span class="attribute-item badge bg-light text-dark border border-1">
                                            {{ $value }}
                                        </span>
                                        @endforeach
                                        @endif
                                        @endforeach
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
                        <img src="{{ $brand->brand_image }}" alt="Ulina Instagram" /
                            style="width: 200px; height: 200px;">
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

<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger intent="WELCOME" chat-title="Bot Tư Vấn" agent-id="0fc7ed94-b173-499d-852f-d4cb8410ce77"
    language-code="vi"></df-messenger>
@endsection
