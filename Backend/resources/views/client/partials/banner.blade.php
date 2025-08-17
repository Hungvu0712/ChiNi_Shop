@if($banner)
<div class="banner-container position-relative mb-5">
    <img src="{{ $banner->banner_image }}" alt="{{ $banner->title }}" class="img-fluid w-100 banner-bg">

    <div class="banner-overlay d-flex align-items-center justify-content-start">
        <div class="container px-4">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12">
                    <div class="banner-content text-white">
                        <h1 class="fw-bold display-5 mb-3">{{ $banner->title }}</h1>

                        @if($banner->content)
                            <div class="lead mb-4">{!! $banner->content !!}</div>
                        @endif

                        @if($banner->link)
                            <a href="{{ $banner->link }}" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                                Xem ngay
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-info">Không có banner hiển thị.</div>
@endif
<style>
.banner-container {
    position: relative;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.banner-bg {
    height: 450px;
    object-fit: cover;
    width: 100%;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 2rem 0;
    background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.2));
}

.banner-content h1 {
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.8);
}

.banner-content .lead {
    font-size: 1.15rem;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}

@media (max-width: 768px) {
    .banner-bg {
        height: 300px;
    }

    .banner-content {
        text-align: center;
    }

    .banner-overlay {
        justify-content: center;
        text-align: center;
        background: rgba(0,0,0,0.5);
    }

    .banner-content h1 {
        font-size: 1.8rem;
    }
}
</style>
