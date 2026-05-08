<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Details - Furniture</title>
    @include('includes.head')
</head>
<body>

<!-- Preloader -->
@include('includes.preloader')

<!-- Header Area -->
@include('includes.header')

<!-- Breadcumb Area -->
<section class="breadcumb-area bg-img2 parallax mt-170 mb-100 jarallax" data-jarallax data-speed="0.2">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcumb-wrap">
                    <h2>Blog Details</h2>
                    <ul class="breadcumb-link">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('blog') }}">Blog</a></li>
                        <li>Blog Details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Details Area -->
<section class="blog-details-area pt-100 pb-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="blog-post">
                    <div class="blog-img">
                        <a href="#"><img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('assets/img/blog-details/post-01.jpg') }}" alt="{{ $blog->title }}"></a>
                    </div>
                    <div class="blog-content pt-30">
                        <ul class="post-meta">
                            <li><i class="far fa-calendar"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'No Date' }}</li>
                            <li><i class="far fa-user"></i> By Admin</li>
                            <li><i class="far fa-comments"></i> 0 Comment</li>
                        </ul>
                        <h3 class="blog-title"><a href="#">{{ $blog->title }}</a></h3>
                        <p>{!! $blog->description !!}</p>
                        
                        <div class="blog-footer">
                            <div class="tag-line">
                                <h6>Tags :</h6>
                                <a href="#">funiture,</a>
                                <a href="#">design,</a>
                                <a href="#">room</a>
                            </div>
                            <div class="blog-share">
                                <h6>Share :</h6>
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-pinterest-p"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Tags & Social Share -->
                <div class="tags-social mt-50">
                    <a href="#" class="btn btn-primary">furniture</a>
                    <a href="#" class="btn btn-primary">design</a>
                    <a href="#" class="btn btn-primary">room</a>
                </div>

                <!-- Comment Area -->
                <div class="comment-area pt-60">
                    <h5 class="comment-heading">Comments</h5>
                    <div class="comment-list">
                        <div class="single-comment d-flex">
                            <img src="{{ asset('assets/img/blog-details/comment-img.png') }}" alt="">
                            <div class="comment-body">
                                <h5>Robert Fox <span>25 Nov 2023</span></h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius odio necessitatibus
                                    beatae laboriosam eligendi minima. </p>
                                <a href="#" class="btn-reply">Reply</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reply Form -->
                <div class="reply-form-area pt-60">
                    <h5 class="comment-heading">Leave A Reply</h5>
                    <form action="#" class="reply-form">
                        <div class="row">
                            <div class="col-lg-4">
                                <input type="text" placeholder="Your Name*">
                            </div>
                            <div class="col-lg-4">
                                <input type="email" placeholder="Your Email*">
                            </div>
                            <div class="col-lg-4">
                                <input type="text" placeholder="Website">
                            </div>
                            <div class="col-12">
                                <textarea name="message" cols="30" rows="6" placeholder="Message*"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Post Comment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4 mt-50 mt-lg-0">
                <div class="widget-area">
                    <!-- Search Widget -->
                    <div class="widget search-widget">
                        <h5 class="widget-title">Search</h5>
                        <form action="#" class="search-wrap">
                            <input type="text" placeholder="Search">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>

                    <!-- Category Widget -->
                    <div class="widget category-widget">
                        <h5 class="widget-title">Categories</h5>
                        <ul>
                            <li><a href="#">Bedroom Furniture</a></li>
                            <li><a href="#">Dinning Table</a></li>
                            <li><a href="#">Sofa</a></li>
                            <li><a href="#">Office Furniture</a></li>
                        </ul>
                    </div>

                    <!-- Recent Post Widget -->
                    <div class="widget recent-post-widget">
                        <h5 class="widget-title">Recent Post</h5>
                        @foreach(App\Models\Blog::latest()->take(3)->get() as $recentBlog)
                        <div class="recent-single-post">
                            <div class="img">
                                <a href="{{ route('blog.details', $recentBlog->slug) }}"><img src="{{ asset('storage/' . $recentBlog->image) }}" alt=""></a>
                            </div>
                            <div class="content">
                                <h6><a href="{{ route('blog.details', $recentBlog->slug) }}">{{ Str::limit($recentBlog->title, 30) }}</a></h6>
                                <span class="date">{{ $recentBlog->published_at ? $recentBlog->published_at->format('M d') : '' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Tags Widget -->
                    <div class="widget tag-widget">
                        <h5 class="widget-title">Tags</h5>
                        <a href="#" class="btn-tag">Bedroom</a>
                        <a href="#" class="btn-tag">Dinning</a>
                        <a href="#" class="btn-tag">Furniture</a>
                        <a href="#" class="btn-tag">Office</a>
                        <a href="#" class="btn-tag">Sofa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Area -->
@include('includes.footer')

<!-- JS here -->
@include('includes.foot')

</body>
</html>

