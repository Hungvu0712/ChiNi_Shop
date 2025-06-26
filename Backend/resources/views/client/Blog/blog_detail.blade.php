@extends('client.layouts.master')
@section('title', 'Chini Shop')
@section('css')

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
    <section class="pageBannerSection singleBlogPageBanner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="pageBannerContent text-center">
                        <h2>Who is happy will make others happy too</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Page Banner Section -->

    <!-- BEGIN: Blog Page Section -->
    <section class="blogDetailsPageSection">
        <div class="container">
            <div class="row">
                @if ($postId)
                    <div class="col-lg-10 offset-lg-1">
                        <div class="blogDetailsThumb">
                            {{-- <img src="" style="width: 500" height="300" alt="Blog Details" /> --}}
                        </div>
                        <div class="blogDetailsContentArea">
                            <div class="bi01Meta clearfix">
                                <span><i class="fa-solid fa-folder-open"></i><a
                                        href="blog_grid_nsb.html">{{ $postId->postCategory->name }}</a></span>
                                <span><i class="fa-solid fa-clock"></i><a
                                        href="blog_grid_nsb.html">{{ $postId->created_at->format('d/m/Y') }}
                                    </a></span>
                                <span><i class="fa-solid fa-comments"></i><a href="javascript:void(0);">78</a></span>
                            </div>
                            <h2 class="postTitle">{{ $postId->title }}</h2>
                            <div class="blogDetailscontent">
                                <p>
                                    {!! $postId->content !!}
                                </p>
                            </div>
                        </div>
                        <div class="postNavigationRow clearfix">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="blog_details_rsb.html" class="postNavigationItem">
                                        <img src="images/news/25.jpg" alt="Blog Item">
                                        <h4><i class="fa-solid fa-angle-left"></i>Previous Post</h4>
                                        <h3>When the musics over turn off the light</h3>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="blog_details_rsb.html" class="postNavigationItem pniRight">
                                        <img src="images/news/26.jpg" alt="Blog Item">
                                        <h4>Next Post<i class="fa-solid fa-angle-right"></i></h4>
                                        <h3>When the musics over turn off the light</h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="postAuthorBox clearfix">
                            <img src="images/author/10.jpg" alt="Author Image">
                            <h3>James Anderson</h3>
                            <div class="pabSocial">
                                <a class="fac" href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a>
                                <a class="twi" href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a>
                                <a class="ins" href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                            <p>
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum. Excep qui officia de
                                serunt mollit anim id est labo rum. Sed ut
                                perspiciatis unde omnis iste natus error sit voluptatem accusantium dolo
                            </p>
                            <a href="blog_grid_rsb.html" class="ulinaLink">View All Posts<i
                                    class="fa fa-angle-right"></i></a>
                        </div>
                        <div class="postCommetnListBox clearfix">
                            <h3 class="commentHeading">20 Comments</h3>
                            <ol class="comment-list">
                                <li class="clearfix">
                                    <div class="singleComment">
                                        <img src="images/author/11.jpg" alt="Author Image">
                                        <h3>Mike Anderson</h3>
                                        <div class="commentTime"><span>June 16, 2022</span><span> at 11.55 pm</span></div>
                                        <div class="commentContent">
                                            Ratione voluptatem sequi nesciu nim ad minim veniam, quis nostrud exercitation
                                            ullam
                                            co laboris nisi ut aliquip ex ea commodo
                                            conse quat. Duis aute irure dolor in repre hen derit in voluptate velit take
                                            niye
                                            thake mogon nishhim trisnay.
                                        </div>
                                        <a class="comment-reply" href="javascript:void(0);">Reply</a>
                                    </div>
                                </li>
                                <li class="clearfix">
                                    <div class="singleComment">
                                        <img src="images/author/12.jpg" alt="Author Image">
                                        <h3>Shanta Arefin</h3>
                                        <div class="commentTime"><span>June 16, 2022</span><span> at 11.55 pm</span></div>
                                        <div class="commentContent">
                                            Ratione voluptatem sequi nesciu nim ad minim veniam, quis nostrud exercitation
                                            ullam
                                            co laboris nisi ut aliquip ex ea commodo
                                            conse quat. Duis aute irure dolor in repre hen derit in voluptate velit take
                                            niye
                                            thake mogon nishhim trisnay.
                                        </div>
                                        <a class="comment-reply" href="javascript:void(0);">Reply</a>
                                    </div>
                                    <ul>
                                        <li class="clearfix">
                                            <div class="singleComment">
                                                <img src="images/author/13.jpg" alt="Author Image">
                                                <h3>Paul Harrison</h3>
                                                <div class="commentTime"><span>June 16, 2022</span><span> at 11.55
                                                        pm</span>
                                                </div>
                                                <div class="commentContent">
                                                    Ratione voluptatem sequi nesciu nim ad minim veniam, quis nostrud
                                                    exercitation ullam co laboris nisi ut aliquip ex ea commodo
                                                    conse quat. Duis aute irure dolor in repre hen derit in voluptate velit
                                                    take
                                                    niye thake mogon nishhim trisnay.
                                                </div>
                                                <a class="comment-reply" href="javascript:void(0);">Reply</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="clearfix">
                                    <div class="singleComment">
                                        <img src="images/author/14.jpg" alt="Author Image">
                                        <h3>Sarah Jeferson</h3>
                                        <div class="commentTime"><span>June 16, 2022</span><span> at 11.55 pm</span></div>
                                        <div class="commentContent">
                                            Ratione voluptatem sequi nesciu nim ad minim veniam, quis nostrud exercitation
                                            ullam
                                            co laboris nisi ut aliquip ex ea commodo
                                            conse quat. Duis aute irure dolor in repre hen derit in voluptate velit take
                                            niye
                                            thake mogon nishhim trisnay.
                                        </div>
                                        <a class="comment-reply" href="javascript:void(0);">Reply</a>
                                    </div>
                                </li>
                            </ol>
                        </div>
                        <div class="postCommetnFormBox clearfix">
                            <h3 class="commentHeading">Add A Comment</h3>
                            <form method="post" action="#" class="commentForm row">
                                <div class="col-md-6">
                                    <input type="text" name="comName" placeholder="Your name">
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="comEmail" placeholder="Your email">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="comPhone" placeholder="Your phone">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="comURL" placeholder="Website">
                                </div>
                                <div class="col-md-12">
                                    <textarea name="comContent" placeholder="Write your comment here"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="ulinaBTN"><span>Submit Now</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('script')

@endsection
