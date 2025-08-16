@php use Illuminate\Support\Str; @endphp
@extends('client.layouts.master')
@section('title', 'Trang chủ')
@section('css')
<style>
    /* Tối ưu hover sản phẩm */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .product-img-container {
        position: relative;
        overflow: hidden;
        padding-top: 100%; /* Tỉ lệ 1:1 */
    }

    .product-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.3s ease;
    }

    .product-img.hover-img {
        opacity: 0;
    }

    .product-card:hover .main-img {
        opacity: 0;
    }

    .product-card:hover .hover-img {
        opacity: 1;
    }

    /* Nút action */
    .product-actions a {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: white;
        color: #333;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .product-actions a:hover {
        background: #7b9691;
        color: white;
        transform: scale(1.1);
    }

    /* Màu sắc */
    .color-option {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.2s ease;
    }

    .color-option:hover {
        transform: scale(1.2);
    }

    .color-option.active {
        border-color: #000;
    }

    /* Thuộc tính sản phẩm */
    .attribute-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
    }

    .attribute-badge:hover {
        background: #000 !important;
        color: white !important;
    }

    /* Section tiêu đề */
    .section-title {
        position: relative;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: #7b9691;
    }

    /* Feature boxes */
    .feature-box {
        padding: 2rem 1.5rem;
        border-radius: 0.5rem;
        height: 100%;
        transition: transform 0.3s ease;
        background: #f8f9fa;
    }

    .feature-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .feature-box i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #7b9691;
    }
</style>
@endsection

@include('client.partials.banner')

@section('content')
<!-- Feature Section - Bootstrap 5 -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <i class="fas fa-truck"></i>
                    <h3 class="h5 mb-3">Miễn phí vận chuyển</h3>
                    <p class="mb-0">Giao hàng nhanh chóng và hoàn toàn miễn phí cho mọi đơn hàng.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <i class="fas fa-credit-card"></i>
                    <h3 class="h5 mb-3">Thanh toán an toàn</h3>
                    <p class="mb-0">Hỗ trợ nhiều phương thức thanh toán bảo mật và tiện lợi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <i class="fas fa-exchange-alt"></i>
                    <h3 class="h5 mb-3">Đổi trả dễ dàng</h3>
                    <p class="mb-0">Đổi trả sản phẩm nhanh chóng trong vòng 7 ngày nếu có lỗi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <i class="fas fa-headset"></i>
                    <h3 class="h5 mb-3">Hỗ trợ 24/7</h3>
                    <p class="mb-0">Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn mọi lúc.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Arrival Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-title">Sản phẩm</h2>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($products as $product)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="product-card card h-100 border-0">
                    <div class="product-img-container position-relative">
                        <img class="product-img main-img"
                             src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                             alt="{{ $product->name }}">
                        <img class="product-img hover-img position-absolute top-0 start-0"
                             src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                             alt="{{ $product->name }}">
                    </div>

                    <div class="card-body">
                        <h5 class="card-title mb-2">
                            <a href="{{ route('client.shop.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                        </h5>

                        <div class="mb-3">
                            @if (!empty($product->colorData))
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach ($product->colorData as $color)
                                <span class="color-option"
                                      style="background-color: {{ $color['hex'] }};"
                                      data-image="{{ asset($color['image']) }}"
                                      data-name="{{ $color['variant_name'] }}"
                                      data-price="{{ number_format($color['price']) }} VNĐ"
                                      title="{{ ucfirst($color['name']) }}"
                                      data-bs-toggle="tooltip"></span>
                                @endforeach
                            </div>
                            @endif

                            @foreach ($product->attributesGroup as $name => $values)
                                @if ($name != 'Màu sắc')
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    @foreach ($values as $value)
                                    <span class="attribute-badge bg-light text-dark border">{{ $value }}</span>
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger fw-bold">{{ number_format($product->price ?? 0) }} VNĐ</span>
                            <a href="{{ route('client.shop.show', $product->slug) }}" class="btn btn-sm btn-outline-dark">
                                <i class="fas fa-eye me-1"></i> Xem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Deal Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="pe-lg-5">
                    <h2 class="mb-4">Hãy tận hưởng tuổi trẻ của bạn!</h2>
                    <p class="lead">Không chỉ là thời trang, CHINISHOP còn là "phòng thí nghiệm" của tuổi trẻ - nơi nghiên cứu và cho ra đời nguồn năng lượng mang tên "Youth".</p>
                    <p>Chúng mình luôn muốn tạo nên những trải nghiệm vui vẻ, năng động và trẻ trung.</p>
                    <a href="#" class="btn btn-dark mt-3">Khám phá ngay</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('client/images/home1/7.png') }}" alt="Enjoy Your Youth" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="section-title">Tin Tức</h2>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('blog') }}" class="btn btn-outline-dark">Xem tất cả</a>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($blogs as $blog)
            @if ($blog->status == 'published')
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ $blog->featured_image }}" class="card-img-top" alt="{{ $blog->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-secondary">{{ $blog->postCategory->name }}</span>
                            <small class="text-muted">{{ $blog->created_at->format('d/m/Y') }}</small>
                        </div>
                        <h5 class="card-title">
                            <a href="{{ route('blog_detail', ['slug' => $blog->slug]) }}" class="text-decoration-none text-dark">{{ Str::limit($blog->title, 50) }}</a>
                        </h5>
                        <p class="card-text">{{ Str::limit(strip_tags($blog->content), 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('blog_detail', ['slug' => $blog->slug]) }}" class="btn btn-sm btn-outline-dark">Đọc tiếp</a>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2>BRAND HỢP TÁC CHINISHOP</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-4">
                    @foreach ($brands as $brand)
                    <div class="brand-item p-3 bg-white rounded shadow-sm">
                        <img src="{{ $brand->brand_image }}" alt="{{ $brand->name }}" style="height: 80px; width: auto; object-fit: contain;">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@section('script')
<script>
    // Color picker functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.color-option').forEach(picker => {
            picker.addEventListener('click', function() {
                const imageUrl = this.dataset.image;
                const name = this.dataset.name;
                const price = this.dataset.price;

                const card = this.closest('.product-card');

                // Update images
                if (imageUrl) {
                    card.querySelector('.main-img').src = imageUrl;
                    card.querySelector('.hover-img').src = imageUrl;
                }

                // Update name if available
                if (name && card.querySelector('.card-title a')) {
                    card.querySelector('.card-title a').innerText = name;
                }

                // Update price if available
                if (price && card.querySelector('.text-danger')) {
                    card.querySelector('.text-danger').innerText = price;
                }

                // Update active state
                card.querySelectorAll('.color-option').forEach(el => el.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>


@endsection
