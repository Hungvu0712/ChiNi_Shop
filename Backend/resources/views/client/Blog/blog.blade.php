@extends('client.layouts.master')
@section('title', 'Chini Shop')
@section('css')

@endsection
{{-- @include('client.partials.banner') --}}
@section('content')

    <!-- BEGIN: Search Popup Section -->
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
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Latest News</h2>
                        <div class="pageBannerPath">
                            <a href="index.html">Home</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Blog</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Page Banner Section -->

    <!-- BEGIN: Blog Page Section -->
    <section class="blogPageSection">
        <div class="container">
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-md-4 mb-4">
                        <div class="blogItem03">
                            <div class="bi03Thumb">
                                <img src="{{ $post->featured_image }}" alt="Ulina Blog Post">
                            </div>
                            <div class="bi03Details">
                                <div class="bi01Meta clearfix">
                                    <span><i class="fa-solid fa-folder-open"></i><a
                                            href="">{{ $post->title }}</a></span>
                                    <span><i class="fa-solid fa-clock"></i><a
                                            href="#">{{ $post->created_at->format('d/m/Y') }}
                                        </a></span>
                                </div>
                                <h3><a href="{{ route('blog_detail', ['id' => $post->id]) }}">{{ $post->excerpt }}</a></h3>
                                <div class="post-category mt-2">
                                    <strong>Danh mục:</strong> <a href="#"
                                        class="text-decoration-underline">{{ $post->postCategory->name }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </section>
    <!-- END: Blog Page Section -->
@endsection
@section('script')

@endsection
