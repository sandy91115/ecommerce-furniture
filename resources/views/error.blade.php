<!-- resources/views/error.blade.php -->
@extends('layouts.main')

@php
    $statusCode = (int) ($statusCode ?? 404);
    $errorTitle = $errorTitle ?? ($statusCode === 404 ? 'Page not found' : 'Something went wrong');
    $errorMessage = $errorMessage ?? 'Sorry for the inconvenience. Please go back home or try again after some time.';
    $statusDigits = str_split((string) $statusCode);
@endphp

@section('title', $statusCode . ' ' . $errorTitle)

@section('content')



<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">{{ $statusCode }} {{ $errorTitle }}</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">{{ $statusCode }}</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Error Area Start -->
<div class="s-py-100 overflow-hidden">
    <div class="container">
        <div class="flex items-center justify-center gap-5">
            @foreach($statusDigits as $index => $digit)
                @if($index === 1 && $digit === '0')
                    <img class="w-36 sm:w-44 md:w-auto" src="{{ asset('assets/img/thumb/error.png') }}" alt="0" data-aos="zoom-in">
                @else
                    <h2 class="text-6xl sm:text-[100px] md:text-[150px] font-semibold leading-none" data-aos="{{ $index === 0 ? 'fade-right' : 'fade-left' }}">{{ $digit }}</h2>
                @endif
            @endforeach
        </div>
        <div class="max-w-[603px] mx-auto text-center mt-8 md:mt-12">
            <h2 data-aos="fade-up" class="text-4xl font-bold">Oops Sorry! {{ $errorTitle }}</h2>
            <p class="mt-4 md:mt-6" data-aos="fade-up">{{ $errorMessage }}</p>
            <div data-aos="fade-up" data-aos-delay="100">
                <a class="btn btn-outline mt-4 md:mt-6" href="{{ url('/') }}" data-text="Go Back To Home">
                    <span>Go Back To Home</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Error Area End -->

@include('includes.footer')
  
@endsection
