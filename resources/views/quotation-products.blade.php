<!-- resources/views/quotation-products.blade.php -->
@extends('layouts.main')

@section('title', 'Quotation Request')

@section('content')

    <!-- Banner Start -->
    <div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70"
        style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
        <div class="text-center w-full">
            <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Quotation Request</h2>
            <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4 flex-wrap">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>/</li>
                <li class="text-primary">Quotation Request</li>
            </ul>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Quotation Products Start -->
    <div class="s-py-100">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto">
                <!-- Section Title -->
                <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
                    <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">Custom Furniture Quotation</h3>
                    <p class="mt-3">Request a custom quote for made-to-order furniture. Tell us your requirements and we'll provide a personalized quote.</p>
                </div>

                @if($products->count() > 0)
                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-[30px]">
                        @foreach($products as $product)
                            <div class="group relative bg-white dark:bg-gray-800 shadow-[0_0_20px_rgba(0,0,0,0.08)] hover:shadow-[0_0_30px_rgba(0,0,0,0.12)] transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <!-- Product Image -->
                                <div class="relative overflow-hidden aspect-[4/5]">
                                    @if($product->images->first())
                                        <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <img src="{{ asset('assets/img/product/default.jpg') }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @endif
                                    
                                    <!-- Quick View & Wishlist -->
                                    <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <button type="button" onclick="event.preventDefault()" class="w-9 h-9 flex items-center justify-center bg-white shadow-md hover:bg-primary hover:text-white transition-colors duration-300" aria-label="Quick View">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Product Content -->
                                <div class="p-4 sm:p-6">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                        {{ optional($product->category)->name ?? 'Custom Furniture' }}
                                    </p>
                                    <h4 class="text-lg font-medium text-title dark:text-white mb-2 line-clamp-2">
                                        <a href="{{ route('product-details', $product->slug) }}" class="hover:text-primary transition-colors">
                                            {{ $product->name }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 mb-4">
                                        {{ Str::limit($product->short_description, 80) }}
                                    </p>
                                    
                                    <!-- Price & Button -->
                                    <div class="flex items-center justify-between gap-3">
                                        <a href="{{ route('quotation.form', $product->slug) }}" 
                                           class="btn btn-outline text-sm py-2 px-4" 
                                           data-text="Get Quote">
                                            <span>Get Quote</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-10 flex justify-center">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16" data-aos="fade-up">
                        <div class="max-w-md mx-auto">
                            <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h4 class="mt-4 text-xl font-medium text-title dark:text-white">No Quotation Products Available</h4>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">Check back later for custom furniture options.</p>
                            <a href="{{ url('/shop') }}" class="btn btn-outline mt-6" data-text="Browse Shop">
                                <span>Browse Shop</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Quotation Products End -->

@endsection
