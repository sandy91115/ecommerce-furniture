@extends('layouts.main')

@section('title', 'Wishlist')

@section('content')
<div class="s-py-100">
    <div class="container">
        <div class="max-w-2xl mx-auto" data-aos="fade-up">
            <div class="flex items-center gap-3 mb-10">
                <a href="/" class="inline-flex items-center gap-2 text-title dark:text-white text-base hover:text-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Home
                </a>
                <span class="text-primary font-semibold">/ Wishlist</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold mb-12">Wishlist</h1>

            @if ($wishlistItems->count() > 0)
            <div class="space-y-6">
                @foreach ($wishlistItems as $product)
                <div class="flex gap-6 p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-dark-secondary shadow-sm">
                    <a href="{{ route('product-details', $product->slug) }}" class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden flex-shrink-0">
                        @php $imagePath = $product->images->first()?->path; @endphp
                        <img src="{{ $imagePath ? asset('storage/' . $imagePath) : asset('assets/img/product/default.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/img/product/default.jpg') }}';">
                    </a>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl font-semibold dark:text-white mb-2">
                            <a href="{{ route('product-details', $product->slug) }}" class="hover:text-primary">{{ $product->name }}</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 100) }}</p>
                        <div class="flex items-center gap-4 flex-wrap">
                            <span class="text-2xl font-bold text-primary">
                                @if($product->sale_price)
                                ${{ number_format($product->sale_price, 2) }}
                                <span class="text-lg line-through text-gray-500">${{ number_format($product->price, 2) }}</span>
                                @else
                                ${{ number_format($product->price, 2) }}
                                @endif
                            </span>
                            <div class="flex gap-2">
                                <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary px-6 py-2">Add to Cart</button>
                                </form>
                                <form action="{{ route('wishlist.remove', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium px-4 py-2 border border-red-500 rounded-lg hover:bg-red-50 transition-all">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="text-2xl font-semibold mb-4 dark:text-white">Your wishlist is empty</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-8">Start adding items to your wishlist.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection