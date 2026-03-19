@extends('layouts.main')

@section('title', ($store['name'] ?? 'Store') . ' - Furnixar')

@section('content')
<!-- Store Header -->
<section class="py-20 bg-gradient-to-r from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <!-- Store Logo & Banner -->
            <div class="flex-shrink-0">
                @if(!empty($store['logo_path']))
                    <img src="{{ asset('storage/' . $store['logo_path']) }}" alt="{{ $store['name'] ?? 'Store' }}" class="w-32 h-32 rounded-full shadow-2xl object-cover border-4 border-white" onerror="this.style.display='none'">
                @else
                    <div class="w-32 h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-2xl">
                        <i class="fas fa-store text-4xl text-white"></i>
                    </div>
                @endif
            </div>
            
            <!-- Store Info -->
            <div class="flex-1 text-center md:text-left">
<h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-4">
                    {{ $store['name'] ?? 'Store' }}
                </h1>
                <div class="flex flex-wrap gap-4 justify-center md:justify-start mb-6">
                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">{{ $products->total() }} Products</span>
                </div>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">{{ $store['description'] ?? '' }}</p>
                <div class="flex flex-wrap gap-4 text-lg font-medium">
                    @if(!empty($store['phone']))
                        <a href="tel:{{ $store['phone'] }}" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-phone mr-2"></i>{{ $store['phone'] }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Store Products -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-gray-100">Store Products</h2>
            <div class="text-sm text-gray-500">
                Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8">
            @forelse ($products as $product)
                <div class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden">
                    <div class="h-64 overflow-hidden relative bg-gradient-to-br from-gray-50 to-gray-100">
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-cube text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 space-y-1">
                            @if($product->sale_price)
                                <span class="px-2 py-1 bg-red-500 text-white text-xs rounded-full font-semibold">Sale</span>
                            @endif
                            <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">
                                {{ $product->variations->count() }} Var.
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            <span class="text-xs bg-gray-100 text-gray-800 px-3 py-1 rounded-full font-medium">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                            <a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-sm text-gray-600 space-x-1">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 fill-current {{ $i <= ($product->avg_rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 15 14">
                                            <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span>({{ $product->reviews->count() ?? 0 }})</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">
                                @if($product->sale_price)
                                    ${{ number_format($product->sale_price, 2) }}
                                    <span class="text-lg text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                @else
                                    ${{ number_format($product->price, 2) }}
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-layer-group mr-1"></i>
                                {{ $product->variations->count() }} Variations
                            </div>
                            <div class="flex space-x-2">
                                <button class="p-3 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all wishlist-toggle" data-product-id="{{ $product->id }}" title="Wishlist">
                                    <svg class="w-5 h-5 text-gray-600 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                <button class="p-3 bg-blue-100 hover:bg-blue-200 rounded-xl transition-all add-to-cart" data-product-id="{{ $product->id }}" title="Add to Cart">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.6 8c-.3.8-.8 1.2-1.5 1.2H2v-2h1.9c.3 0 .5-.2.5-.5l.4-2.8c.1-.7.3-1.3.7-1.7l1.2-1.2c.4-.4.9-.6 1.4-.6h5.1c.5 0 .9.2 1.3.6l1.3 1.2c.4.4.6.9.7 1.7l.5 2.8c0 .3.2.5.5.5H20v2h-1.9c-.7 0-1.2-.4-1.5-1.2l-1.6-8z"></path>
                                    </svg>
                                </button>
                                <a href="{{ route('product-details', $product->slug) }}" class="p-3 bg-green-100 hover:bg-green-200 rounded-xl transition-all quick-view" title="Quick View">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <i class="fas fa-store-slash text-6xl text-gray-300 mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Products Available</h3>
                    <p class="text-gray-500 mb-8">This store hasn't added any products yet.</p>
                    <a href="/" class="bg-gray-900 hover:bg-gray-800 text-white px-8 py-4 rounded-xl font-semibold">
                        Continue Shopping
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection

