@extends('layouts.main')

@section('content')
@php
    $content = is_array($page->content) ? implode("\n", $page->content) : (string) $page->content;
@endphp

<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h1 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">{{ $page->title }}</h1>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">{{ $page->title }}</li>
        </ul>
    </div>
</div>

<main class="s-py-100">
    <div class="container-fluid">
        <article class="max-w-[980px] mx-auto prose prose-lg max-w-none dark:prose-invert">
            {!! $content !!}
        </article>
    </div>
</main>

@include('includes.footer')
@endsection
