<!-- resources/views/quotation-history.blade.php -->
@extends('layouts.main')

@section('title', 'Quotation History Page')

@section('content')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Quotation History</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Quotation History</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Quotation History Start -->
<div class="s-py-100" data-aos="fade-up">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto flex items-start gap-8 md:gap-12 2xl:gap-24 flex-col md:flex-row my-profile-navtab">
            <div class="w-full md:w-[200px] lg:w-[300px] flex-none">
                <ul class="divide-y dark:divide-paragraph text-title dark:text-white text-base sm:text-lg lg:text-xl flex flex-col justify-center leading-none">
                    <li class="pb-3 lg:pb-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/my-account') }}">My Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/edit-account') }}">Edit Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/order-history') }}">Order History</a>
                    </li>
                    <li class="active text-primary py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ route('quotation-history') }}">Quotation Product</a>
                    </li>
                    <li class="pt-3 lg:pt-6 pl-6 lg:pl-12"><a class="duration-300 hover:text-primary" href="{{ url('/login') }}">Logout</a></li>
                </ul>
            </div>
            <div class="w-full md:w-auto md:flex-1 overflow-auto">
                <div class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px] order-history-table">
                    <h3 class="text-2xl font-semibold mb-6 text-title dark:text-white">Quotation History ({{ $quotations->total() ?? 0 }})</h3>
                    @if(isset($quotations) && $quotations->count() > 0)
                    {{ $quotations->links() }}
                    <ul class="order-history">
                        <!-- Table Heading -->
                        <li class="title flex items-center justify-between gap-5 pb-[10px] sm:pb-5 border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <span class="cart-product-title text-lg md:text-xl font-semibold leading-none text-title dark:text-white block w-[270px] sm:w-[310px] xl:w-[330px]">Product</span>
                            <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[100px]">Status</span>
                            <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[80px]">Price</span>
                            <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[80px]">Date</span>
                        </li>
                        @foreach($quotations as $quotation)
                        @php
                            $productImage = $quotation->product->images->first()
                                ? asset('storage/' . $quotation->product->images->first()->path)
                                : asset('assets/img/product/default.jpg');
                            $statusColor = match($quotation->status) {
                                'pending' => '#EC991D',
                                'contacted' => '#3B82F6',
                                'closed' => '#31A051',
                                default => '#6B7280',
                            };
                        @endphp
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ $productImage }}" alt="{{ $quotation->product->name }}" class="rounded-lg object-cover w-full h-16 sm:h-[90px]">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">{{ $quotation->customer_name }}</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4 text-xl">
                                        <a href="{{ route('product-details', $quotation->product->slug) }}">{{ $quotation->product->name }}</a>
                                    </h5>
                                    <p class="text-sm text-gray-500 mt-1">{{ $quotation->city }}, {{ $quotation->country }}</p>
                                </div>
                            </div>

                            <span style="background-color: {{ $statusColor }};" class="py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded w-[100px] text-center">
                                {{ ucfirst($quotation->status) }}
                            </span>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[80px]">
                                {{ $quotation->desired_price ? currency($quotation->desired_price) : 'N/A' }}
                            </span>

                            <span class="text-sm text-title dark:text-white w-[80px]">{{ $quotation->created_at->format('M d, Y') }}</span>
                        </li>
                        @endforeach
                    </ul>
                    {{ $quotations->links() }}
                    @else
                    <p class="text-center text-title dark:text-white py-8">No quotation requests found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Quotation History End -->

@include('includes.footer')

@endsection
