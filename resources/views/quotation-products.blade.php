@extends('layouts.main')

@section('title', 'Quotation Products')

@section('content')
    <div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70"
        style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
        <div class="text-center w-full">
            <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Quotation Products</h2>
            <ul
                class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>/</li>
                <li class="text-primary">Quotation Products</li>
            </ul>
        </div>
    </div>

    <div class="s-py-100">
        <div class="container-fluid">
            <div
                class="max-w-[1720px] mx-auto flex items-start justify-between gap-8 flex-col lg:flex-row border-b border-bdr-clr dark:border-bdr-clr-drk pb-8 md:pb-[50px]">
                <div class="max-w-3xl">
                    <h3 class="text-3xl md:text-4xl font-semibold leading-none">Quote-Only Furniture Collection</h3>
                    <p class="mt-4 text-lg text-title/70 dark:text-white/70">Only those products are being shown here that
                        are available for custom quotation. You can open a product and send an inquiry via WhatsApp.</p>

                </div>
                <div
                    class="w-full max-w-md rounded-[24px] border border-bdr-clr dark:border-bdr-clr-drk bg-white dark:bg-dark-secondary p-6">
                    <p class="text-sm uppercase tracking-[0.2em] text-primary font-semibold">Live Count</p>
                    <h4 class="mt-3 text-3xl font-semibold">{{ $products->total() }}</h4>
                    <p class="mt-2 text-sm text-title/70 dark:text-white/70">Active quotation products available right now.
                    </p>
                </div>
            </div>

            <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-8 md:pt-[50px]"
                data-aos="fade-up" data-aos-delay="200">
                @forelse($products as $product)
                    <div
                        class="group bg-white dark:bg-dark-secondary rounded-xl shadow-lg hover:shadow-xl transition-all overflow-hidden relative">
                        <a href="{{ route('product-details', $product->slug) }}" class="absolute inset-0 z-10"
                            aria-label="View {{ $product->name }}"></a>
                        <div class="overflow-hidden h-64 relative">
                            <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->path) : asset('assets/img/product/default.jpg') }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-duration-500">
                        </div>
                        <div class="p-6 relative z-20">
                            <div class="mb-3 flex items-center gap-2 flex-wrap">
                                <span
                                    class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-black">Quotation
                                    Product</span>
                                @if($product->category)
                                    <span
                                        class="inline-flex items-center rounded-full bg-title/5 px-3 py-1 text-xs font-semibold text-title/70 dark:bg-white/10 dark:text-white/70">{{ $product->category->name }}</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-lg mb-3">{{ $product->name }}</h3>
                            @if($product->short_description)
                                <p class="text-sm text-title/70 dark:text-white/70 mb-5 line-clamp-3">
                                    {!! strip_tags($product->short_description) !!}</p>
                            @endif
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-lg font-bold text-primary">Custom quotation</span>
                                <a href="{{ route('product-details', $product->slug) }}" class="btn btn-solid btn-sm"
                                    data-text="Open Product">
                                    <span>Open Product</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <h4 class="text-2xl font-semibold">No quotation products available</h4>
                        <p class="mt-3 text-title/70 dark:text-white/70">Abhi koi quote-only product active nahi hai. Thodi der
                            baad dobara check karo.</p>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="max-w-[1720px] mx-auto mt-12">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>


    @include('includes.footer')
@endsection