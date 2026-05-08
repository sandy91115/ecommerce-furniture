@php
    $featuredProductItems = collect($products ?? [])->take(8);
    $showFeaturedPromo = ($featuredPromo['enabled'] ?? true);
    $featuredPromoRawUrl = $featuredPromo['button_url'] ?? route('quotation-products.index');
    $featuredPromoRawUrl = filled($featuredPromoRawUrl) ? $featuredPromoRawUrl : route('quotation-products.index');
    $featuredPromoHref = \Illuminate\Support\Str::startsWith($featuredPromoRawUrl, ['http://', 'https://', 'mailto:', 'tel:', '#'])
        ? $featuredPromoRawUrl
        : url($featuredPromoRawUrl);
@endphp

<div class="featured-products-layout">
    <div class="featured-products-grid featured-products-grid--catalog grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8">
        @foreach ($featuredProductItems as $product)
            @php
                $primaryImage = $product->images->sortByDesc(fn($image) => (int) $image->featured)->first();
                $hasDiscount = $product->sale_price && (float) $product->sale_price < (float) $product->price;
                $discount = $hasDiscount && (float) $product->price > 0
                    ? round((((float) $product->price - (float) $product->sale_price) / (float) $product->price) * 100)
                    : null;
            @endphp

            <article class="featured-product-card group">
                <a href="{{ route('product-details', ['slug' => $product->slug]) }}" class="featured-product-card__media">
                    <img
                        class="featured-product-card__image"
                        src="{{ image_url($primaryImage) }}"
                        alt="{{ $product->name }}"
                        loading="lazy"
                    >
                    @if($discount)
                        <span class="featured-product-card__badge">Save {{ $discount }}%</span>
                    @endif
                </a>

                <div class="featured-product-card__body">
                    <h4 class="featured-product-card__price">
                        @if($product->sale_price)
                            {{ currency($product->sale_price) }}
                            <span>{{ currency($product->price) }}</span>
                        @else
                            {{ currency($product->price) }}
                        @endif
                    </h4>

                    <h5 class="featured-product-card__title">
                        <a href="{{ route('product-details', ['slug' => $product->slug]) }}">
                            {{ $product->name }}
                        </a>
                    </h5>

                    <div class="featured-product-card__rating" aria-label="4 out of 5 stars">
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span>&#9733;</span>
                        <span class="is-muted">&#9733;</span>
                        <small>({{ $product->product_rating_count ?: 45 }})</small>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    @if($showFeaturedPromo)
        <a href="{{ $featuredPromoHref }}" class="featured-promo-card featured-promo-card--inline group">
            <span class="featured-promo-card__media">
                <img
                    class="featured-promo-card__image"
                    src="{{ $featuredPromo['image_url'] ?? asset('assets/img/home-v1/choose-us-bg.jpg') }}"
                    alt="{{ $featuredPromo['title'] ?? 'Custom furniture orders' }}"
                    loading="lazy"
                >
            </span>

            <span class="featured-promo-card__content">
                <span class="featured-promo-card__label">{{ $featuredPromo['label'] ?? 'Custom Orders' }}</span>
                <strong>{{ $featuredPromo['title'] ?? 'Need a made-to-measure piece?' }}</strong>
                <span class="featured-promo-card__desc">{{ $featuredPromo['description'] ?? 'Share your size, finish and resin color requirements. Our team will help craft a coffee table for your space.' }}</span>
                <span class="featured-promo-card__cta">{{ $featuredPromo['button_text'] ?? 'Request Custom Order' }}</span>
            </span>
        </a>
    @endif
</div>
