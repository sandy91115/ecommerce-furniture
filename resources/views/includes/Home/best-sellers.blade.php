@foreach ($products ?? [] as $product)
<div class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden">
    <div class="h-64 overflow-hidden relative bg-gradient-to-br from-gray-50 to-gray-100">
        @if($product->images->first())
            <img src="{{ asset('storage/' . $product->images->first()->image) }}" 
                 alt="{{ $product->name }}" 
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
                {{ $product->category->name ?? 'Furniture' }}
            </span>
        </div>
<h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
            <a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center text-sm text-gray-600 space-x-1">
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 fill-current {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 15 14">
                            <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                        </svg>
                    @endfor
                </div>
                <span>({{ $product->reviews_count ?? 123 }})</span>
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
            <a href="{{ route('product-details', $product->slug) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex-1 text-center mr-2 quick-view">
                View Details
            </a>
            <button class="p-3 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-300 ml-2 wishlist-toggle" data-product-id="{{ $product->id }}">
                <svg class="w-5 h-5 text-gray-600 group-hover:text-red-500" fill="currentColor" viewBox="0 0 24 21">
                    <!-- Heart SVG path simplified -->
                </svg>
            </button>
            <button class="p-3 bg-blue-100 hover:bg-blue-200 rounded-xl transition-all duration-300 ml-2 add-to-cart" data-product-id="{{ $product->id }}">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 23">
                    <!-- Cart SVG -->
                </svg>
            </button>
        </div>
    </div>
</div>
@endforelse
@if(empty($products ?? []))
<div class="col-span-full text-center py-12">
    <i class="fas fa-fire text-6xl text-gray-300 mb-6"></i>
    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Best Sellers Yet</h3>
</div>
@endif
