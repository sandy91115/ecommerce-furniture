@extends('layouts.main')

@section('content')
    @php
        $wishlistIds = array_map('intval', $wishlistIds ?? array_keys(session('wishlist', [])));
        $isInWishlist = in_array((int) $product->id, $wishlistIds, true);
        $formattedWhatsappNumber = preg_replace('/\D+/', '', (string) ($whatsappNumber ?? '')) ?: '1234567890';
        $gallery = $product->images->isNotEmpty() ? $product->images : collect([(object) ['path' => null]]);
        $galleryItems = $gallery->map(function ($image, $index) use ($product) {
            $fallbackImage = asset('assets/img/product/default.jpg');
            $hasImage = (bool) data_get($image, 'path');
            $largeImageUrl = $hasImage ? image_url($image, 'large', $fallbackImage) : $fallbackImage;

            return [
                'url' => $largeImageUrl,
                'medium' => $hasImage ? image_url($image, 'medium', $largeImageUrl) : $largeImageUrl,
                'thumb' => $hasImage ? image_url($image, 'thumb', $largeImageUrl) : $largeImageUrl,
                'srcset' => $hasImage ? image_srcset($image) : '',
                'alt' => data_get($image, 'alt') ?: $product->name . ' image ' . ($index + 1),
                'width' => data_get($image, 'width') ?: 1000,
                'height' => data_get($image, 'height') ?: 1000,
            ];
        })->values();
        $primaryImage = $galleryItems->first();
        $price = $product->sale_price ?: $product->price;
        $hasDiscount = $product->sale_price && (float) $product->sale_price < (float) $product->price;
        $discountPercentage = $hasDiscount && (float) $product->price > 0
            ? round((((float) $product->price - (float) $product->sale_price) / (float) $product->price) * 100)
            : null;
        $approvedReviewModels = $product->reviews->where('status', 'approved');
        $displayRatingValue = filled($product->product_rating)
            ? (float) $product->product_rating
            : ($approvedReviewModels->isNotEmpty() ? (float) $approvedReviewModels->avg('rating') : null);
        $displayRating = $displayRatingValue ? number_format($displayRatingValue, 1) : null;
        $displayRatingCount = filled($product->product_rating_count) ? (int) $product->product_rating_count : $approvedReviewModels->count();
        $productUrl = route('product-details', $product->slug);
        $shareUrl = rawurlencode($productUrl);
        $shareText = rawurlencode($product->name);
        $enquiryWhatsappMessage = rawurlencode("Hi! I want to know more about {$product->name}. {$productUrl}");
        $quoteWhatsappMessage = rawurlencode("Hi! I want a quote for {$product->name}. {$productUrl}");
        $productIntro = \Illuminate\Support\Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags($product->short_description ?: $product->description ?: 'Product details will be updated soon.'))),
            260
        );
        $productDescription = $product->description ?: '<p>Description will be updated soon.</p>';
        $productTags = collect([
            optional($product->category)->name,
            optional($product->material)->name,
            optional($product->color)->name,
        ])->merge($product->attributeMaps->map(fn($attributeMap) => optional($attributeMap->attribute)->name))
            ->filter()
            ->unique()
            ->values();
        $specifications = collect([
            $product->material ? 'Material : ' . $product->material->name : null,
            $product->category ? 'Category : ' . $product->category->name : null,
            $product->color ? 'Color : ' . $product->color->name : null,
            $product->sku ? 'SKU : ' . $product->sku : null,
            $product->warranty_months ? 'Warranty : ' . $product->warranty_months . ' months' : null,
            'Availability : ' . ($product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock'),
        ])->filter()->values();
        $shopName = data_get($product, 'vendor.store_name') ?: config('app.name', 'Furniture Store');
        $productCategoryUrl = $product->category
            ? route('shop.category', ['category' => $product->category->slug])
            : route('shop');
        $vendorName = data_get($product, 'vendor.user.name') ?: 'Support Team';
        $vendorEmail = data_get($product, 'vendor.user.email');
        $vendorPhone = data_get($product, 'vendor.store_phone') ?: data_get($product, 'vendor.user.phone');
        $vendorAddress = data_get($product, 'vendor.store_address');
        $vendorExtraInfo = $product->extra_description ?: (data_get($product, 'vendor.store_description') ? nl2br(e(data_get($product, 'vendor.store_description'))) : null);
        $technicalSpecifications = collect($product->technical_specifications ?? [])->filter(fn($row) => filled($row['field'] ?? '') || filled($row['value'] ?? ''))->values();
        $customizationOptions = collect($product->customization_options ?? [])->filter(fn($row) => filled($row['category'] ?? '') || filled($row['choices'] ?? ''))->values();
        $productFaqs = collect($product->faqs ?? [])->filter(fn($row) => filled($row['question'] ?? '') || filled($row['answer'] ?? ''))->values();
        $careAndMaintenance = trim((string) ($product->care_and_maintenance ?? ''));
        $shippingDetails = trim((string) ($product->shipping_details ?? ''));
        $productReviews = $product->reviews
            ->where('status', 'approved')
            ->filter(fn($review) => filled($review->comment) || filled($review->title) || filled($review->reviewer_name))
            ->sortByDesc('created_at')
            ->values()
            ->map(function ($review) {
                $comment = filled($review->comment)
                    ? $review->comment
                    : ($review->title ?? 'Customer shared a positive experience with this product.');

                return [
                    'id' => $review->id,
                    'name' => $review->reviewer_name ?: ($review->user->name ?? 'Anonymous'),
                    'rating' => $review->rating,
                    'title' => $review->title ?? \Illuminate\Support\Str::limit((string) $comment, 100, ''),
                    'comment' => $comment,
                    'status' => $review->status,
                    'review' => $review,
                    'created_at' => $review->created_at,
                ];
            });
        $customerSayReviews = $productReviews->isNotEmpty()
            ? $productReviews
            : collect($testimonials ?? [])->filter(fn($review) => filled($review->comment))->map(function ($review) {
                return [
                    'id' => $review->id,
                    'name' => $review->reviewer_name ?: ($review->user->name ?? 'Anonymous'),
                    'rating' => $review->rating,
                    'title' => $review->title ?? \Illuminate\Support\Str::limit((string) $review->comment, 100, ''),
                    'comment' => $review->comment,
                    'status' => $review->status,
                    'review' => $review,
                    'created_at' => $review->created_at,
                ];
            });
    @endphp

    <div class="product-breadcrumb-bar bg-[#F8F5F0] dark:bg-dark-secondary py-5 md:py-[30px]">
        <div class="container-fluid">
            <ul
                class="flex items-center gap-[10px] text-base md:text-lg leading-none font-normal text-title dark:text-white max-w-[1720px] mx-auto flex-wrap">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>/</li>
                <li><a href="{{ route('shop') }}">Shop</a></li>
                @if($product->category)
                    <li>/</li>
                    <li><a href="{{ $productCategoryUrl }}">{{ $product->category->name }}</a></li>
                @endif
                <li>/</li>
                <li class="text-primary">{{ $product->name }}</li>
            </ul>
        </div>
    </div>

    @if (session('success'))
        <div class="container-fluid mt-8">
            <div
                class="max-w-[1720px] mx-auto rounded-[20px] border border-[#1CB28E]/20 bg-[#1CB28E]/10 px-6 py-4 text-[#1C7B64]">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="s-py-50 product-detail-overview" data-aos="fade-up" data-product-slug="{{ $product->slug }}">
        <div class="container-fluid">
            <div class="product-detail-shell max-w-[1720px] mx-auto flex justify-between gap-10 flex-col lg:flex-row">
                <div class="product-gallery-column w-full lg:w-[58%]">
                    <div class="relative">
                        
                        <div class="product-gallery" data-product-gallery
                            data-product-images='@json($galleryItems, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)'
                            data-product-name="{{ e($product->name) }}"
                            data-product-price="{{ e(currency($price)) }}">
                            <div class="product-gallery__main">
                                <button type="button" class="product-gallery__stage" data-product-lightbox-open
                                    aria-label="Open {{ $product->name }} image gallery">
                                    <span class="product-gallery__hint">Zoom image</span>
                                    <span class="product-gallery__zoom-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                                            <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.7"></circle>
                                            <path d="M20 20L15.5 15.5" stroke="currentColor" stroke-width="1.7"
                                                stroke-linecap="round"></path>
                                            <path d="M11 8.5V13.5M8.5 11H13.5" stroke="currentColor" stroke-width="1.7"
                                                stroke-linecap="round"></path>
                                        </svg>
                                    </span>
                                    <span class="product-gallery__zoom-frame">
                                        <img src="{{ $primaryImage['medium'] ?? $primaryImage['url'] }}"
                                            srcset="{{ $primaryImage['srcset'] }}"
                                            sizes="(max-width: 1023px) 100vw, 58vw"
                                            alt="{{ $primaryImage['alt'] }}" class="product-gallery__main-image"
                                            width="{{ $primaryImage['width'] ?? 1000 }}" height="{{ $primaryImage['height'] ?? 1000 }}"
                                            data-product-main-image loading="eager" decoding="async"
                                            onerror="this.onerror=null; this.src='{{ asset('assets/img/product/default.jpg') }}';">
                                    </span>
                                </button>
                            </div>

                            @if($galleryItems->count() > 1)
                                <div class="product-gallery__thumbs" role="tablist" aria-label="Product image thumbnails">
                                    @foreach($galleryItems as $image)
                                        <button type="button" class="product-gallery__thumb {{ $loop->first ? 'is-active' : '' }}"
                                            data-product-thumb data-index="{{ $loop->index }}" data-image-url="{{ $image['url'] }}"
                                            data-image-alt="{{ $image['alt'] }}" aria-label="View image {{ $loop->iteration }}"
                                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                            <img src="{{ $image['thumb'] }}" alt="{{ $image['alt'] }}"
                                                width="{{ $image['width'] ?? 200 }}" height="{{ $image['height'] ?? 200 }}"
                                                loading="lazy" decoding="async"
                                                onerror="this.onerror=null; this.src='{{ asset('assets/img/product/default.jpg') }}';">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:max-w-[635px] w-full product-detail-info">
                    <div class="product-info-head pb-4 sm:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk">
                        @if($product->category)
                            <a href="{{ $productCategoryUrl }}" class="product-detail-kicker">{{ $product->category->name }}</a>
                        @endif
                        <h2 class="font-semibold leading-tight md:text-4xl">{{ $product->name }}</h2>

                        <div class="product-summary-strip mt-3">
                            <span class="product-stock-pill {{ $product->stock > 0 ? 'is-in-stock' : 'is-out-stock' }}">
                                <span aria-hidden="true">{!! $product->stock > 0 ? '&#10003;' : '!' !!}</span>
                                {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>

                            @if($displayRating)
                                <span class="product-rating-pill">
                                    <strong>{{ $displayRating }}</strong>
                                    <span class="product-rating-stars" aria-label="{{ $displayRating }} out of 5 stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= round((float) $displayRating) ? 'is-filled' : '' }}">&#9733;</span>
                                        @endfor
                                    </span>
                                    @if($displayRatingCount)
                                        <span class="product-rating-count">({{ $displayRatingCount }})</span>
                                    @endif
                                </span>
                            @endif
                        </div>

                        <div class="product-price-row mt-[15px]">
                            @if($hasDiscount)
                                <span class="product-old-price">{{ currency($product->price, 0) }}</span>
                            @endif

                            <span class="product-current-price">
                                {{ currency($price, 0) }}
                            </span>

                            @if($discountPercentage)
                                <span class="product-discount-pill">{{ $discountPercentage }}% Off</span>
                            @endif
                        </div>

                        <p class="sm:text-lg mt-5 md:mt-7">{{ $productIntro }}</p>
                    </div>

                    <div class="product-action-panel py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up"
                        data-aos-delay="200">
                        @if($isQuotationProduct)
                            <div class="rounded-[24px] bg-[#FAF2F2] dark:bg-dark-secondary p-5 sm:p-6">
                                <h4 class="text-xl md:text-[22px] font-semibold !leading-none">Need Custom Pricing?</h4>
                                <p class="sm:text-lg mt-3">Fill the quote form or send a WhatsApp message with quantity, size,
                                    finish and delivery city.</p>
                                <div class="flex gap-4 mt-4 sm:mt-6 flex-col sm:flex-row">
                                    <button type="button" class="btn btn-solid " data-text="Quote Now" data-quote-trigger
                                        data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                        data-product-price="{{ currency($price, 0) }}">
                                        <span>Quote Now</span>
                                    </button>
                                    <a href="https://wa.me/{{ $formattedWhatsappNumber }}?text={{ $quoteWhatsappMessage }}"
                                        target="_blank" rel="noopener noreferrer" class="btn btn-solid " data-text="WhatsApp">
                                        <span>WhatsApp</span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="product-quantity-row">
                                    <span class="product-quantity-label">Quantity</span>
                                    <div class="inc-dec flex items-center gap-2">
                                        <button type="button"
                                            class="dec w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                                            <svg class="fill-current text-title dark:text-white" width="14" height="2"
                                                viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.4361 0.203613H12.0736L7.81774 0.203615H13.8729V1.80309H7.81774L3.50809 1.80309H1.87053L6.18017 1.80309H0.125V0.203615H6.18017L10.4361 0.203613Z" />
                                            </svg>
                                        </button>
                                        <input id="productQuantity"
                                            class="w-10 h-auto outline-none bg-transparent text-base md:text-lg leading-none text-title dark:text-white text-center"
                                            type="text" name="quantity" value="1">
                                        <button type="button"
                                            class="inc w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                                            <svg class="fill-current text-title dark:text-white" width="14" height="14"
                                                viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.18017 0.110352H7.81774V6.16553H13.8729V7.76501H7.81774V13.8963H6.18017V7.76501H0.125V6.16553H6.18017V0.110352Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex gap-4 mt-4 sm:mt-6 flex-col sm:flex-row">
                                    <button type="submit" class="flex-1 btn btn-solid "
                                        data-text="{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}" {{ $product->stock < 1 ? 'pointer-events-none opacity-60' : '' }} {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        <span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                                    </button>

                                    <button type="submit" name="redirect_to"
                                        value="{{ route('frontend.checkout', [], false) }}"
                                        class="flex-1 btn btn-solid"
                                        data-text="{{ $product->stock > 0 ? 'Buy Now' : 'Out of Stock' }}" {{ $product->stock < 1 ? 'pointer-events-none opacity-60' : '' }} {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        <!-- <svg class="fill-current text-title dark:text-white w-5 h-5"
                                            width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17 18H7C5.89543 18 5 17.1046 5 16V8H19V16C19 17.1046 18.1046 18 17 18ZM7 6V5C7 3.34315 8.34315 2 10 2H14C15.6569 2 17 3.34315 17 5V6H21V8H3V6H7ZM9 6H15V5C15 4.44772 14.5523 4 14 4H10C9.44772 4 9 4.44772 9 5V6Z" />
                                        </svg> -->
                                        <span>{{ $product->stock > 0 ? 'Buy Now' : 'Out of Stock' }}</span>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                    @if($product->sku || $product->category || $product->material || $product->color || $product->warranty_months || $product->attributeMaps->isNotEmpty())
                        <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk product-detail-meta" data-aos="fade-up"
                            data-aos-delay="300">
                            <div class="product-meta-grid">
                                @if($product->sku)
                                    <h6>SKU <span>{{ $product->sku }}</span></h6>
                                @endif
                                @if($product->category)
                                    <h6>Category <span>{{ $product->category->name }}</span></h6>
                                @endif
                                @if($product->material)
                                    <h6>Material <span>{{ $product->material->name }}</span></h6>
                                @endif

                                @if($product->color)
                                    <h6>Color <span>{{ $product->color->name }}</span></h6>
                                @endif

                                @if($product->warranty_months)
                                    <h6>Warranty <span>{{ $product->warranty_months }} months</span></h6>
                                @endif
                            </div>

                            @if($product->attributeMaps->isNotEmpty())
                                <div class="product-feature-tags">
                                    <h6>Features</h6>
                                    <div>
                                        @foreach($product->attributeMaps as $attributeMap)
                                            @if($attributeMap->attribute)
                                                <span>{{ $attributeMap->attribute->name }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($productTags->isNotEmpty())
                        <div class="product-tags-block py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up"
                            data-aos-delay="400">
                            <h4 class="font-medium leading-none text-2xl">Tags</h4>
                            <div class="product-tags-list">
                                @foreach($productTags as $tag)
                                    <span class="product-tag-pill">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                   
                </div>
            </div>
        </div>
    </div>

    @include('includes.Shop.product-benefits')

    <div class="s-py-50 product-detail-tabs-section">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto">
                <div class="product-dtls-navtab border-y border-bdr-clr dark:border-bdr-clr-drk hidden md:block">
                    <ul id="user-nav-tabs"
                        class="text-title dark:text-white text-base sm:text-lg lg:text-xl flex leading-none gap-3 sm:gap-6 md:gap-12 lg:gap-24 justify-between sm:justify-start max-w-md sm:max-w-full">
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0 active">
                            <a class="duration-300 hover:text-primary" href="#content1" data-product-tab="1">Description</a>
                        </li>
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0">
                            <a class="duration-300 hover:text-primary" href="#content2" data-product-tab="2">Technical Specifications</a>
                        </li>
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0">
                            <a class="duration-300 hover:text-primary" href="#content3" data-product-tab="3">FAQ</a>
                        </li>
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0">
                            <a class="duration-300 hover:text-primary" href="#content4" data-product-tab="4">Customization Options</a>
                        </li>
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0">
                            <a class="duration-300 hover:text-primary" href="#content5" data-product-tab="5">Care And Maintenance</a>
                        </li>
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0">
                            <a class="duration-300 hover:text-primary" href="#content6" data-product-tab="6">Shipping</a>
                        </li>
                    </ul>
                </div>

                <div id="content" class="mt-5 sm:mt-8 lg:mt-12 mx-0 hidden md:block">
                    <div id="content1" class="product-tab-panel is-active">
                        <h3 class="mb-4 text-2xl font-semibold text-title dark:text-white">Description</h3>
                        <div class="sm:text-lg">{!! $productDescription !!}</div>

                        @if($specifications->isNotEmpty())
                            <ul class="mt-4 sm:mt-6 grid gap-4 sm:gap-5 sm:text-lg leading-none">
                                @foreach($specifications as $specification)
                                    <li>{{ $specification }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div id="content2" class="product-tab-panel">
                        <h3 class="mb-4 text-2xl font-semibold text-title dark:text-white">Technical Specifications</h3>
                        @if($technicalSpecifications->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[640px] border-collapse text-left sm:text-lg">
                                    <thead>
                                        <tr class="border-b border-bdr-clr dark:border-bdr-clr-drk">
                                            <th class="py-3 pr-5 font-semibold">Specs Field</th>
                                            <th class="py-3 pr-5 font-semibold">Example Value</th>
                                            <th class="py-3 font-semibold">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($technicalSpecifications as $spec)
                                            <tr class="border-b border-bdr-clr/60 dark:border-bdr-clr-drk">
                                                <td class="py-4 pr-5 font-medium text-title dark:text-white">{{ $spec['field'] ?? '' }}</td>
                                                <td class="py-4 pr-5">{{ $spec['value'] ?? '' }}</td>
                                                <td class="py-4">{{ $spec['notes'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif($specifications->isNotEmpty())
                            <ul class="grid gap-4 sm:gap-5 sm:text-lg leading-none">
                                @foreach($specifications as $specification)
                                    <li>{{ $specification }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="sm:text-lg">Technical specifications will be updated soon.</p>
                        @endif
                    </div>

                    <div id="content3" class="product-tab-panel">
                        <h3 class="mb-4 text-2xl font-semibold text-title dark:text-white">FAQs</h3>
                        @if($productFaqs->isNotEmpty())
                            <div class="grid gap-5">
                                @foreach($productFaqs as $faq)
                                    <div class="border-b border-bdr-clr pb-5 dark:border-bdr-clr-drk">
                                        <h4 class="text-xl font-semibold leading-snug">{{ $faq['question'] ?? '' }}</h4>
                                        <p class="mt-3 sm:text-lg">{{ $faq['answer'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="sm:text-lg">FAQs will be updated soon.</p>
                        @endif
                    </div>

                    <div id="content4" class="product-tab-panel">
                        <h3 class="mb-4 text-2xl font-semibold text-title dark:text-white">Customization Options</h3>
                        @if($customizationOptions->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[640px] border-collapse text-left sm:text-lg">
                                    <thead>
                                        <tr class="border-b border-bdr-clr dark:border-bdr-clr-drk">
                                            <th class="py-3 pr-5 font-semibold">Option Category</th>
                                            <th class="py-3 pr-5 font-semibold">Choices Available</th>
                                            <th class="py-3 font-semibold">Applies To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customizationOptions as $option)
                                            <tr class="border-b border-bdr-clr/60 dark:border-bdr-clr-drk">
                                                <td class="py-4 pr-5 font-medium text-title dark:text-white">{{ $option['category'] ?? '' }}</td>
                                                <td class="py-4 pr-5">{{ $option['choices'] ?? '' }}</td>
                                                <td class="py-4">{{ $option['applies_to'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="sm:text-lg">Customization options will be updated soon.</p>
                        @endif
                    </div>

                    <div id="content5" class="product-tab-panel">
                        <h3 class="mb-4 text-2xl font-semibold text-title dark:text-white">Care And Maintenance</h3>
                        <div class="care-maintenance-panel">
                            @if($careAndMaintenance !== '')
                                <div class="sm:text-lg leading-relaxed">{!! nl2br(e($careAndMaintenance)) !!}</div>
                            @else
                                <p class="sm:text-lg">Care and maintenance details will be updated soon.</p>
                            @endif
                        </div>
                    </div>

                    <div id="content6" class="product-tab-panel">
                        <h3 class="mb-4 text-2xl font-semibold text-title dark:text-white">Shipping</h3>
                        @if($shippingDetails !== '')
                            <div class="product-shipping-panel sm:text-lg leading-relaxed">{!! nl2br(e($shippingDetails)) !!}</div>
                        @else
                            @include('includes.Shop.shipping')
                        @endif
                    </div>
                </div>

                <div class="product-detail-accordion md:hidden">
                    <details class="product-detail-accordion__item" open>
                        <summary>
                            <span>Description</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </summary>
                        <div class="product-detail-accordion__panel">
                            <div>{!! $productDescription !!}</div>
                            @if($specifications->isNotEmpty())
                                <ul>
                                    @foreach($specifications as $specification)
                                        <li>{{ $specification }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </details>

                    <details class="product-detail-accordion__item">
                        <summary>
                            <span>Technical Specifications</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </summary>
                        <div class="product-detail-accordion__panel">
                            @if($technicalSpecifications->isNotEmpty())
                                <div class="product-mobile-table">
                                    @foreach($technicalSpecifications as $spec)
                                        <div class="product-mobile-table__row">
                                            <strong>{{ $spec['field'] ?? '' }}</strong>
                                            <span>{{ $spec['value'] ?? '' }}</span>
                                            @if(filled($spec['notes'] ?? ''))
                                                <small>{{ $spec['notes'] }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($specifications->isNotEmpty())
                                <ul>
                                    @foreach($specifications as $specification)
                                        <li>{{ $specification }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Technical specifications will be updated soon.</p>
                            @endif
                        </div>
                    </details>

                    <details class="product-detail-accordion__item">
                        <summary>
                            <span>FAQ</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </summary>
                        <div class="product-detail-accordion__panel">
                            @if($productFaqs->isNotEmpty())
                                <div class="grid gap-4">
                                    @foreach($productFaqs as $faq)
                                        <div>
                                            <h4>{{ $faq['question'] ?? '' }}</h4>
                                            <p>{{ $faq['answer'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>FAQs will be updated soon.</p>
                            @endif
                        </div>
                    </details>

                    <details class="product-detail-accordion__item">
                        <summary>
                            <span>Customization Options</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </summary>
                        <div class="product-detail-accordion__panel">
                            @if($customizationOptions->isNotEmpty())
                                <div class="product-mobile-table">
                                    @foreach($customizationOptions as $option)
                                        <div class="product-mobile-table__row">
                                            <strong>{{ $option['category'] ?? '' }}</strong>
                                            <span>{{ $option['choices'] ?? '' }}</span>
                                            @if(filled($option['applies_to'] ?? ''))
                                                <small>{{ $option['applies_to'] }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>Customization options will be updated soon.</p>
                            @endif
                        </div>
                    </details>

                    <details class="product-detail-accordion__item">
                        <summary>
                            <span>Care And Maintenance</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </summary>
                        <div class="product-detail-accordion__panel">
                            @if($careAndMaintenance !== '')
                                <div>{!! nl2br(e($careAndMaintenance)) !!}</div>
                            @else
                                <p>Care and maintenance details will be updated soon.</p>
                            @endif
                        </div>
                    </details>

                    <details class="product-detail-accordion__item">
                        <summary>
                            <span>Shipping</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </summary>
                        <div class="product-detail-accordion__panel">
                            @if($shippingDetails !== '')
                                <div>{!! nl2br(e($shippingDetails)) !!}</div>
                            @else
                                @include('includes.Shop.shipping')
                            @endif
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>

    @if($customerSayReviews->isNotEmpty())
        <section class="product-customer-say s-py-50">
            <div class="container-fluid">
                <div class="max-w-[1720px] mx-auto">
                    <div class="product-section-heading text-center">
                        <h3>Customers Say!</h3>
                        <p>Real feedback from verified customers.</p>
                    </div>

                    <div class="product-carousel-shell mt-8" data-simple-carousel data-autoplay="true">
                        <button type="button" class="product-carousel-arrow product-carousel-arrow--prev" data-carousel-prev aria-label="Previous customer review">&#8249;</button>
                        <div class="product-carousel-track product-review-strip" data-carousel-track>
                            @foreach($customerSayReviews as $review)
                                <article class="customer-say-card" data-carousel-item>
                                    <div class="customer-say-card__top">
                                        <div class="customer-say-card__avatar">{{ strtoupper(\Illuminate\Support\Str::substr($review['name'], 0, 1)) }}</div>
                                        <div>
                                            <div class="customer-say-card__stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="{{ $i <= (int) $review['rating'] ? 'is-filled' : '' }}">&#9733;</span>
                                                @endfor
                                            </div>
                                            <h4>{{ $review['name'] }}</h4>
                                            <p>Verified customer</p>
                                        </div>
                                    </div>
                                    <p class="customer-say-card__copy">{{ \Illuminate\Support\Str::limit($review['comment'], 155) }}</p>
                                    <span class="customer-say-card__badge">Verified Purchase</span>
                                </article>
                            @endforeach
                        </div>
                        <button type="button" class="product-carousel-arrow product-carousel-arrow--next" data-carousel-next aria-label="Next customer review">&#8250;</button>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($products->isNotEmpty())
        <div class="s-py-50-100 product-related-section">
            <div class="container-fluid">
                <div class="max-w-[1720px] mx-auto">
                    <div class="product-related-head">
                        <div>
                            <h3>Related Products</h3>
                            <p>More pieces from the same collection.</p>
                        </div>
                        <div class="product-related-controls">
                            <button type="button" data-related-prev aria-label="Previous related product">&#8249;</button>
                            <button type="button" data-related-next aria-label="Next related product">&#8250;</button>
                        </div>
                    </div>
                </div>
                <div class="max-w-[1720px] mx-auto pt-8 md:pt-[34px]">
                    <div class="related-product-slider" data-related-slider>
                        @foreach($products as $relatedProduct)
                            @php
                                $relatedImage = $relatedProduct->images->sortByDesc(fn($image) => (int) $image->featured)->first();
                                $relatedHasDiscount = $relatedProduct->sale_price && (float) $relatedProduct->sale_price < (float) $relatedProduct->price;
                                $relatedDiscount = $relatedHasDiscount && (float) $relatedProduct->price > 0
                                    ? round((((float) $relatedProduct->price - (float) $relatedProduct->sale_price) / (float) $relatedProduct->price) * 100)
                                    : null;
                                $relatedPrice = $relatedProduct->sale_price ?: $relatedProduct->price;
                            @endphp
                            <article class="related-product-card">
                                <a href="{{ route('product-details', $relatedProduct->slug) }}" class="related-product-card__media">
                                    <img src="{{ image_url($relatedImage) }}" alt="{{ $relatedProduct->name }}" loading="lazy">
                                    @if($relatedDiscount)
                                        <span>Save {{ $relatedDiscount }}%</span>
                                    @endif
                                </a>
                                <div class="related-product-card__body">
                                    <h4><a href="{{ route('product-details', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a></h4>
                                    <div class="related-product-card__price">
                                        <strong>{{ currency($relatedPrice, 0) }}</strong>
                                        @if($relatedHasDiscount)
                                            <del>{{ currency($relatedProduct->price, 0) }}</del>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif



    @if($isQuotationProduct)
        @include('quotations.form')
    @endif

    @include('includes.footer')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = Array.from(document.querySelectorAll('[data-product-tab]'));
            const panels = Array.from(document.querySelectorAll('.product-tab-panel'));

            if (!tabs.length || !panels.length) {
                return;
            }

            const activateProductTab = (tabNumber, updateHash = true) => {
                panels.forEach((panel) => {
                    panel.classList.toggle('is-active', panel.id === 'content' + tabNumber);
                });

                tabs.forEach((tab) => {
                    tab.closest('li')?.classList.toggle('active', tab.dataset.productTab === String(tabNumber));
                });

                if (updateHash) {
                    history.replaceState(null, '', '#content' + tabNumber);
                }
            };

            tabs.forEach((tab) => {
                tab.addEventListener('click', function (event) {
                    event.preventDefault();
                    activateProductTab(tab.dataset.productTab);
                });
            });

            const hashMatch = window.location.hash.match(/^#content([1-6])$/);
            activateProductTab(hashMatch ? hashMatch[1] : '1', false);
        });
    </script>
@endpush
