@php
    $isInWishlist = in_array((int) $product->id, $activeWishlistIds ?? [], true);
    $productLink = route('product-details', ['slug' => $product->slug]);
    $primaryImage = $product->images->sortByDesc(fn($image) => (int) $image->featured)->first();
    $hasDiscount = $product->sale_price && (float) $product->sale_price < (float) $product->price;
    $discountPercentage = $hasDiscount && (float) $product->price > 0
        ? round((((float) $product->price - (float) $product->sale_price) / (float) $product->price) * 100)
        : null;
    $isNew = filled($product->created_at) && $product->created_at->greaterThan(now()->subDays(7));
    $badgeText = null;
    $badgeClass = '';

    if ($hasDiscount && $discountPercentage >= 25) {
        $badgeText = 'Hot Sale';
        $badgeClass = 'is-hot';
    } elseif ($isNew) {
        $badgeText = 'NEW';
        $badgeClass = 'is-new';
    } elseif ($hasDiscount && $discountPercentage > 0) {
        $badgeText = $discountPercentage . '% OFF';
        $badgeClass = 'is-off';
    }
@endphp
<div class="home-new-product-card group">
    <div class="home-new-product-card__media">
        <a href="{{ $productLink }}" class="home-new-product-card__image-link">
                <img class="home-new-product-card__image" src="{{ image_url($primaryImage) }}" loading="lazy"
                    alt="{{ $product->name }}">
        </a>

        @if($badgeText)
            <span class="home-new-product-card__badge {{ $badgeClass }}">{{ $badgeText }}</span>
        @endif

        
    </div>

    <div class="home-new-product-card__content">
        @if($product->product_type === 'quotation')
            <span class="inline-flex w-max items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">Quotation Product</span>
        @endif

        <h4 class="home-new-product-card__price font-medium leading-none dark:text-white text-lg">
@if($product->product_type === 'quotation')
                Request Quote
            @elseif($product->sale_price)
                {{ currency($product->sale_price, 2) }}
                <span class="text-title/50 line-through pl-2 inline-block">{{ currency($product->price, 2) }}</span>
            @else
                {{ currency($product->price, 2) }}
            @endif
        </h4>

        <h5 class="home-new-product-card__title font-normal text-xl leading-[1.5]">
            <a href="{{ $productLink }}" class="text-underline">
                {{ $product->name }}
            </a>
        </h5>
        <ul class="home-new-product-card__rating flex items-center gap-2 mt-1">
            <!-- Rating stars - simplified -->
            <li><svg width="15" height="14" viewBox="0 0 15 14" fill="#EE9818"/></li>
            <li class="dark:text-gray-100">(4.5)</li>
        </ul>
    </div>
</div> 

