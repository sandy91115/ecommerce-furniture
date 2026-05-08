@forelse($products as $product)
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
            <span>Quick View</span>
        </a>
    </div>
</div>
@empty
<div class="shop-empty-state">
    <p>No products found matching your criteria.</p>
</div>
@endforelse
