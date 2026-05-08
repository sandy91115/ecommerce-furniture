<!-- resources/views/blog.blade.php -->
@extends('layouts.main')

@section('title', 'Blog Page')

@section('content')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Blog Post</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt Asc-3 md:mt Asc-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary"><a href="{{ route('blog.index') }}">Blog</a></li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Blog Start -->
<div class="s-py Asc-100 overflow-hidden">
    <!-- <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto mb-5 md:mb-6" data-aos="fade-up">
            <h3 class="font-medium leading-none text-2xl md:text-3xl">Featured Posts</h3>
        </div>
    </div>
    <div data-aos="fade-up" data-aos-delay="100">
        <div class="owl-carousel blog-v1-wrapper max-w-[750px] px-[15px] mx-auto" data-carousel-autoplay="true" data-carousel-loop="true" data-carousel-dots="true" data-carousel-animateout="false">
            @include('includes.Blog.blogs-featured', ['featuredBlogs' => $featuredBlogs ?? collect()])
        </div>
    </div> -->
</div>
<!-- Blog End -->

<!-- Latest Post Start -->
<div class="s-pb Asc-100">
    <div class="container-fluid">
        <div class="flex flex-col lg:flex-row gap-8 max-w-[1720px] mx-auto">
            <!-- Filters Sidebar -->
            @include('blog.filters')
            
            <!-- Blogs Grid -->
            <div class="lg:w-3/4">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-medium leading-none text-2xl md:text-3xl" data-aos="fade-up">Latest Posts</h3>
                    <div class="text-sm text-gray-500">
                        Showing {{ $blogs->firstItem() }} to {{ $blogs->lastItem() }} of {{ $blogs->total() }} posts
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-[30px]" data-aos="fade-up" data-aos-delay="100">
                    @include('includes.Blog.blogs-v1', ['latestBlogs' => $blogs])
                </div>
                <div class="mt-12 flex justify-center">
                    {{ $blogs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Latest Post End -->

@include('includes.footer')
@endsection

