@extends('client.layouts.master')
@section('title', 'Chini Shop')
@section('css')
@section('css')
    <style>
        .blogDetailsPageSection {
            margin-top: 60px;
            /* Hoặc chỉnh theo ý */
        }
    </style>
@endsection

@endsection
{{-- @include('client.partials.banner') --}}
@section('content')
<section class="popup_search_sec">
    <div class="popup_search_overlay"></div>
    <div class="pop_search_background">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="popup_logo">
                        <a href="index.html"><img src="images/logo2.png" alt="Ulina"></a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6">
                    <a href="javascript:void(0);" id="search_Closer" class="search_Closer"></a>
                </div>
            </div>
        </div>
        <div class="middle_search">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="popup_search_form">
                            <form method="get" action="#">
                                <input type="search" name="s" id="s"
                                    placeholder="Type Words and Hit Enter">
                                <button type="submit"><i class="fa-solid fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: Search Popup Section -->

<!-- BEGIN: Page Banner Section -->
{{-- <section class="pageBannerSection singleBlogPageBanner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="pageBannerContent text-center">
                        <img src="{{ asset('public/client/images/slider/3.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
<!-- END: Page Banner Section -->

<!-- BEGIN: Blog Page Section -->
<section class="blogDetailsPageSection mt-5">

    <div class="container">
        <div class="row">
            @if ($post)
                <div class="col-lg-10 offset-lg-1">
                    <div class="blogDetailsThumb">
                        {{-- <img src="" style="width: 500" height="300" alt="Blog Details" /> --}}
                    </div>
                    <div class="blogDetailsContentArea">
                        <div class="bi01Meta clearfix">
                            <span><i class="fa-solid fa-folder-open"></i><a
                                    href="blog_grid_nsb.html">{{ $post->postCategory->name }}</a></span>
                            <span><i class="fa-solid fa-clock"></i><a
                                    href="blog_grid_nsb.html">{{ $post->created_at->format('d/m/Y') }}
                                </a></span>
                        </div>
                        <h2 class="postTitle">{{ $post->excerpt }}</h2>
                        <div class="blogDetailscontent">
                            <p>
                                {!! $post->content !!}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <p>k co chi tiet bai viet</p>
            @endif
        </div>
    </div>
</section>
@endsection
@section('script')

@endsection
