@forelse($products as $product)
<<<<<<< HEAD
<div class="shop-product-card group">
    @php($cardImage = $product->images->first())
    <a href="{{ route('product-details', $product->slug) }}" class="shop-product-card__media" aria-label="Open {{ $product->name }}">
        <img src="{{ $cardImage ? asset('storage/' . $cardImage->path) : asset('assets/img/product/default.jpg') }}" 
            alt="{{ $cardImage?->alt ?: $product->name }}" 
            class="shop-product-card__image" 
            width="{{ $cardImage?->width ?: 500 }}"
            height="{{ $cardImage?->height ?: 500 }}"
            loading="lazy"
            decoding="async">
    </a>
    <div class="shop-product-card__body">
        <p class="shop-product-card__category">{{ $product->category->name ?? 'Uncategorized' }}</p>
        <h3 class="shop-product-card__title line-clamp-2">
            <a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        @if($product->product_type === 'quotation')
            <span class="shop-product-card__badge">Quotation</span>
        @endif
        <div class="shop-product-card__price">
            @if($product->product_type === 'quotation')
                <span>Quote Now</span>
            @elseif($product->sale_price)
                <span>{{ currency($product->sale_price, 0) }}</span>
                <del>{{ currency($product->price, 0) }}</del>
            @else
                <span>{{ currency($product->price, 0) }}</span>
            @endif
        </div>
        <a href="{{ route('product-details', $product->slug) }}" class="shop-product-card__button btn btn-solid" data-text="Quick View">
=======
<div class="group">
    <a href="{{ route('product-details', $product->slug) }}" class="block overflow-hidden bg-gray-100" style="aspect-ratio: 1 / 1;" aria-label="Open {{ $product->name }}">
        <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->path) : asset('assets/img/product/default.jpg') }}" 
            alt="{{ $product->name }}" 
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer" 
            loading="lazy">
    </a>
    <div class="p-4 md:p-6">
        <h3 class="font-bold text-lg md:text-xl mb-2 leading-tight line-clamp-2">
            <a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <p class="text-sm md:text-base text-gray-600 mb-3">{{ $product->category->name ?? 'Uncategorized' }}</p>
        @if($product->product_type === 'quotation')
            <span class="inline-flex items-center rounded-full bg-blue-100 text-blue-800 px-3 py-1 text-xs font-semibold mb-3">Quotation</span>
        @endif
        <div class="flex items-center mb-4">
            @if($product->product_type === 'quotation')
                <span class="text-xl font-bold text-primary">Quote Now</span>
            @elseif($product->sale_price)
                <span class="text-2xl font-bold text-primary">{{ currency($product->sale_price, 0) }}</span>
                <span class="ml-2 text-lg text-gray-500 line-through">{{ currency($product->price, 0) }}</span>
            @else
                <span class="text-2xl font-bold text-primary">{{ currency($product->price, 0) }}</span>
            @endif
        </div>
        <a href="{{ route('product-details', $product->slug) }}" class="w-full block text-center btn btn-solid text-sm py-3" data-text="Quick View">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            <span>Quick View</span>
        </a>
    </div>
</div>
@empty
<<<<<<< HEAD
<div class="shop-empty-state">
    <p>No products found matching your criteria.</p>
=======
<div class="col-span-full text-center py-16">
    <p class="text-xl text-gray-500">No products found matching your criteria.</p>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
</div>
@endforelse
