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

@section('content')

<!-- BEGIN: Blog Page Section -->
<section class="blogDetailsPageSection mt-5">

    <div class="container">
        <div class="row">
            @if ($post)
                <div class="col-lg-10 offset-lg-1">
                    <div class="blogDetailsThumb">
                        
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
                        <div>
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
