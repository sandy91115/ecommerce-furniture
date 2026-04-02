@extends('layouts.main')

@section('title', $product->seo_title ?: $product->name)
@section('meta_description', $metaDescription)

@push('head')
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <script type="application/ld+json">
    {!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
    </script>
@endpush

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
            ];
        })->values();
        $primaryImage = $galleryItems->first();
        $price = $product->sale_price ?: $product->price;
        $hasDiscount = $product->sale_price && (float) $product->sale_price < (float) $product->price;
        $discountPercentage = $hasDiscount && (float) $product->price > 0
            ? round((((float) $product->price - (float) $product->sale_price) / (float) $product->price) * 100)
            : null;
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
            !$isQuotationProduct ? 'Availability : ' . ($product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock') : null,
        ])->filter()->values();
        $shopName = data_get($product, 'vendor.store_name') ?: config('app.name', 'Furniture Store');
        $productCategoryUrl = $product->category
            ? route('shop.category', ['category' => $product->category])
            : route('shop');
        $vendorName = data_get($product, 'vendor.user.name') ?: 'Support Team';
        $vendorEmail = data_get($product, 'vendor.user.email');
        $vendorPhone = data_get($product, 'vendor.store_phone') ?: data_get($product, 'vendor.user.phone');
        $vendorAddress = data_get($product, 'vendor.store_address');
        $vendorExtraInfo = $product->extra_description ?: (data_get($product, 'vendor.store_description') ? nl2br(e(data_get($product, 'vendor.store_description'))) : null);
    @endphp

    <div class="bg-[#F8F5F0] dark:bg-dark-secondary py-5 md:py-[30px]">
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

    <div class="s-py-50" data-aos="fade-up" data-product-slug="{{ $product->slug }}">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto flex justify-between gap-10 flex-col lg:flex-row">
                <div class="w-full lg:w-[58%]">
                    <div class="relative">
                        @if($discountPercentage)
                            <button
                                class="absolute top-5 left-0 p-2 !bg-[#E13939] text-lg leading-none text-white font-medium z-50">-{{ $discountPercentage }}%</button>
                        @elseif($isQuotationProduct)
                            <button
                                class="absolute top-5 left-0 p-2 !bg-primary text-lg leading-none text-white font-medium z-50">Quote</button>
                        @endif

                        <div class="product-gallery" data-product-gallery
                            data-product-images='@json($galleryItems, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)'
                            data-product-name="{{ e($product->name) }}"
                            data-product-price="{{ e($isQuotationProduct ? 'Starting from ' . currency($price) : currency($price)) }}">
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
                                                loading="lazy" decoding="async"
                                                onerror="this.onerror=null; this.src='{{ asset('assets/img/product/default.jpg') }}';">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:max-w-[635px] w-full">
                    <div class="pb-4 sm:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span
                                class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-black">{{ $isQuotationProduct ? 'Quotation Product' : 'Ready to Buy' }}</span>
                            @if($product->category)
                                <a href="{{ $productCategoryUrl }}"
                                    class="inline-flex items-center rounded-full bg-title/5 px-3 py-1 text-xs font-semibold text-title/70 transition-colors hover:bg-primary/10 hover:text-primary dark:bg-white/10 dark:text-white/70 dark:hover:text-primary">{{ $product->category->name }}</a>
                            @endif
                        </div>

                        <h2 class="font-semibold leading-none md:text-4xl mt-4">{{ $product->name }}</h2>

                        <div class="flex gap-4 items-center mt-[15px] flex-wrap">
                            @if(!$isQuotationProduct && $hasDiscount)
                                <span
                                    class="text-lg sm:text-xl leading-none pb-[5px] text-title line-through pl-2 inline-block dark:text-white">{{ currency($product->price) }}</span>
                            @endif

                            <span class="text-2xl sm:text-3xl text-primary leading-none block">
                                {{ $isQuotationProduct ? 'Starting from ' : '' }}{{ currency($price) }}
                            </span>

                            @if(!$isQuotationProduct)
                                <span
                                    class="inline-flex items-center rounded-full bg-[#F8F5F0] dark:bg-dark-secondary px-4 py-2 text-sm font-medium">
                                    {{ $product->stock > 0 ? $product->stock . ' items available' : 'Currently out of stock' }}
                                </span>
                            @endif
                        </div>

                        <p class="sm:text-lg mt-5 md:mt-7">{{ $productIntro }}</p>
                    </div>

                    <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up"
                        data-aos-delay="200">
                        @if($isQuotationProduct)
                            <div class="rounded-[24px] bg-[#FAF2F2] dark:bg-dark-secondary p-5 sm:p-6">
                                <h4 class="text-xl md:text-[22px] font-semibold !leading-none">Need Custom Pricing?</h4>
                                <p class="sm:text-lg mt-3">Fill the quote form or send a WhatsApp message with quantity, size,
                                    finish and delivery city.</p>
                                <div class="flex gap-4 mt-4 sm:mt-6 flex-col sm:flex-row">
                                    <button type="button" class="btn btn-solid " data-text="Quote Now" data-quote-trigger
                                        data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                        data-product-price="{{ number_format((float) $price, 2) }}">
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

                                <div class="flex gap-4 mt-4 sm:mt-6 flex-col sm:flex-row">
                                    <button type="submit" class="flex-1 btn btn-solid "
                                        data-text="{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}" {{ $product->stock < 1 ? 'pointer-events-none opacity-60' : '' }} {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        <span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                                    </button>

                                    <button type="button"
                                        class="wishlist-btn flex-1 btn btn-solid {{ $isInWishlist ? 'added' : '' }}"
                                        data-text="{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                                        data-product-id="{{ $product->id }}">
                                        <svg class="fill-current {{ $isInWishlist ? 'text-red-500' : 'text-title dark:text-white' }} w-5 h-5"
                                            width="20" height="22" viewBox="0 0 24 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.3927 0.0917969C15.4463 0.0917969 13.7401 0.959692 12.4584 2.60171C12.2875 2.8207 12.1351 3.03979 12.0001 3.25198C11.865 3.03974 11.7127 2.8207 11.5417 2.60171C10.2601 0.959692 8.55381 0.0917969 6.60743 0.0917969C2.93056 0.0917969 0.300781 3.17049 0.300781 6.86477C0.300781 11.089 3.7629 15.0701 11.5265 19.7733C11.672 19.8614 11.8361 19.9055 12.0001 19.9055C12.1641 19.9055 12.3281 19.8615 12.4737 19.7733C20.2372 15.0702 23.6994 11.089 23.6994 6.86482C23.6994 3.17246 21.0717 0.0917969 17.3927 0.0917969ZM19.4564 12.1247C17.8401 13.9281 15.3977 15.827 12.0001 17.9205C8.60248 15.827 6.16002 13.9281 4.54374 12.1247C2.91873 10.3115 2.1288 8.59096 2.1288 6.86482C2.1288 4.20487 3.92637 1.91981 6.60743 1.91981C7.97277 1.91981 9.13694 2.51346 10.0676 3.6843C10.8118 4.62066 11.1254 5.58754 11.1276 5.59444C11.2466 5.97626 11.6001 6.23634 12.0001 6.23634C12.4001 6.23634 12.7536 5.97631 12.8727 5.59444C12.8756 5.58521 13.1797 4.64849 13.8994 3.72644C14.8351 2.52762 16.0105 1.91976 17.3927 1.91976C20.0766 1.91976 21.8713 4.20702 21.8713 6.86477C21.8713 8.59092 21.0814 10.3114 19.4564 12.1247Z" />
                                        </svg>
                                        <span>{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}</span>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                    @if($product->sku || $product->category || $product->material || $product->color || $product->warranty_months || $product->attributeMaps->isNotEmpty())
                        <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up"
                            data-aos-delay="300">
                            <div class="flex gap-x-12 gap-y-3 flex-wrap">
                                @if($product->sku)
                                    <h6 class="leading-none font-medium text-lg">SKU : {{ $product->sku }}</h6>
                                @endif
                                @if($product->category)
                                    <h6 class="leading-none font-medium text-lg">Category : {{ $product->category->name }}</h6>
                                @endif
                            </div>

                            <div class="flex gap-x-12 lg:gap-x-24 gap-y-3 flex-wrap mt-5 sm:mt-10">
                                @if($product->material)
                                    <div class="flex gap-[10px] items-center flex-wrap">
                                        <h6 class="leading-none font-medium text-lg">Material :</h6>
                                        <div class="flex gap-[10px]">
                                            <span
                                                class="px-3 py-2 text-sm leading-none bg-[#E8E9EA] dark:bg-dark-secondary text-title dark:text-white">{{ $product->material->name }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if($product->color)
                                    <div class="flex gap-[10px] items-center flex-wrap">
                                        <h6 class="leading-none font-medium text-lg">Color :</h6>
                                        <div class="flex gap-[10px] items-center">
                                            <span
                                                class="px-3 py-2 text-sm leading-none bg-[#E8E9EA] dark:bg-dark-secondary text-title dark:text-white">{{ $product->color->name }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if($product->warranty_months)
                                    <div class="flex gap-[10px] items-center flex-wrap">
                                        <h6 class="leading-none font-medium text-lg">Warranty :</h6>
                                        <div class="flex gap-[10px] items-center">
                                            <span
                                                class="px-3 py-2 text-sm leading-none bg-[#E8E9EA] dark:bg-dark-secondary text-title dark:text-white">{{ $product->warranty_months }}
                                                months</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($product->attributeMaps->isNotEmpty())
                                <div class="flex gap-[10px] items-start flex-wrap mt-5 sm:mt-6">
                                    <h6 class="leading-none font-medium text-lg pt-2">Features :</h6>
                                    <div class="flex gap-[10px] flex-wrap">
                                        @foreach($product->attributeMaps as $attributeMap)
                                            @if($attributeMap->attribute)
                                                <span
                                                    class="px-3 py-2 text-sm leading-none bg-[#E8E9EA] dark:bg-dark-secondary text-title dark:text-white">{{ $attributeMap->attribute->name }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($productTags->isNotEmpty())
                        <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up"
                            data-aos-delay="400">
                            <h4 class="font-medium leading-none text-2xl">Tags :</h4>
                            <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                                @foreach($productTags as $tag)
                                    <span class="btn btn-theme-outline btn-xs" data-text="{{ $tag }}"><span>{{ $tag }}</span></span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 sm:pt-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="flex items-center gap-6 flex-wrap">
                            <h6 class="font-normal text-lg">Share : </h6>
                            <div class="flex gap-6">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-paragraph duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                    <svg class="fill-current" width="9" height="17" viewBox="0 0 9 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.85187 2.88048H8.3125V0.327504C7.60589 0.249301 6.89543 0.211267 6.18454 0.213583C5.69283 0.185244 5.2009 0.265194 4.74322 0.447828C4.28554 0.630463 3.87319 0.911363 3.53508 1.27084C3.19696 1.63032 2.94126 2.05967 2.78589 2.52881C2.63052 2.99795 2.57925 3.49553 2.63567 3.98665V6.23546H0.3125V9.09033H2.63567V16.2674H5.4843V9.09033H7.7144L8.06849 6.23546H5.4843V4.26918C5.48543 3.44439 5.70674 2.88048 6.85187 2.88048Z" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="text-paragraph duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                    <svg class="fill-current" width="21" height="17" viewBox="0 0 21 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20.3125 1.93807C19.56 2.26226 18.7641 2.47762 17.9495 2.5775C18.8075 2.07421 19.4491 1.27744 19.7528 0.338011C18.9492 0.809117 18.0701 1.14092 17.1534 1.31907C16.5909 0.726685 15.8612 0.315117 15.0591 0.137768C14.257 -0.0395802 13.4195 0.0254805 12.6553 0.324511C11.891 0.623542 11.2354 1.14273 10.7734 1.81471C10.3114 2.48668 10.0644 3.28041 10.0644 4.09289C10.061 4.40344 10.0927 4.7134 10.1589 5.017C8.52829 4.93856 6.93277 4.52093 5.47658 3.79139C4.02038 3.06186 2.73628 2.03683 1.70816 0.783282C1.18069 1.67484 1.01735 2.73179 1.25147 3.73836C1.48559 4.74493 2.09952 5.62522 2.96794 6.19953C2.31904 6.18223 1.68386 6.01099 1.11593 5.70024V5.74404C1.117 6.6799 1.44419 7.58683 2.04242 8.3122C2.64065 9.03756 3.4734 9.53706 4.40052 9.72665C4.04967 9.81785 3.68811 9.86253 3.32535 9.85951C3.06466 9.86431 2.8042 9.84131 2.54851 9.79089C2.81297 10.5956 3.3235 11.2993 4.00969 11.805C4.69587 12.3107 5.5239 12.5935 6.37955 12.6143C4.92709 13.7358 3.13616 14.3434 1.29315 14.3399C0.965406 14.3422 0.637852 14.3236 0.3125 14.2845C2.18785 15.4772 4.37257 16.1075 6.60256 16.0991C8.13765 16.1094 9.65951 15.8181 11.0798 15.2422C12.5 14.6662 13.7904 13.8171 14.8759 12.7441C15.9614 11.671 16.8204 10.3955 17.403 8.99161C17.9857 7.58769 18.2804 6.08333 18.27 4.56589C18.27 4.38632 18.27 4.21406 18.2552 4.04179C19.0647 3.47007 19.7619 2.75716 20.3125 1.93807Z" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/{{ $formattedWhatsappNumber }}?text={{ rawurlencode('Check out ' . $product->name . ': ' . $productUrl) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="text-paragraph duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                    <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.371-5.05 9.99 9.99 0 0 1 9.88-9.88c5.462 0 9.905 4.444 9.905 9.906a9.89 9.89 0 0 1-9.905 9.906Z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="s-py-50">
        <div class="container-fluid">
            <div class="max-w-[985px] mx-auto">
                <div class="product-dtls-navtab border-y border-bdr-clr dark:border-bdr-clr-drk">
                    <ul id="user-nav-tabs"
                        class="text-title dark:text-white text-base sm:text-lg lg:text-xl flex leading-none gap-3 sm:gap-6 md:gap-12 lg:gap-24 justify-between sm:justify-start max-w-md sm:max-w-full">
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0 active">
                            <a class="duration-300 hover:text-primary" href="#c1">Description</a>
                        </li>
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0">
                            <a class="duration-300 hover:text-primary" href="#c2">Vendor Info</a>
                        </li>
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0">
                            <a class="duration-300 hover:text-primary" href="#c3">Review</a>
                        </li>
                        <li role="presentation"
                            class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0">
                            <a class="duration-300 hover:text-primary" href="#c4">Shipping</a>
                        </li>
                    </ul>
                </div>

                <div id="content" class="mt-5 sm:mt-8 lg:mt-12 mx-0 sm:mr-5 md:mr-8 lg:mr-12">
                    <div id="content1">
                        <div class="sm:text-lg">{!! $productDescription !!}</div>

                        @if($specifications->isNotEmpty())
                            <ul class="mt-4 sm:mt-6 grid gap-4 sm:gap-5 sm:text-lg leading-none">
                                @foreach($specifications as $specification)
                                    <li>{{ $specification }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div id="content2" style="display: none;">
                        <div class="max-w-[680px] flex items-start justify-between gap-y-8 gap-x-10 flex-wrap">
                            <div>
                                <span class="text-primary sm:text-lg leading-none block">Shop Name</span>
                                <h4 class="font-medium mt-2 sm:mt-3 text-xl sm:text-2xl leading-none">{{ $shopName }}</h4>
                                <ul class="mt-4 sm:mt-6 grid gap-3 sm:text-lg">
                                    <li>Vendor : {{ $vendorName }}</li>
                                    @if($vendorAddress)
                                        <li>Address : {{ $vendorAddress }}</li>
                                    @endif
                                    @if($vendorEmail)
                                        <li>Mail : {{ $vendorEmail }}</li>
                                    @endif
                                    @if($vendorPhone)
                                        <li>Call : {{ $vendorPhone }}</li>
                                    @endif
                                </ul>
                            </div>

                            @if($product->extra_title || $vendorExtraInfo)
                                <div class="max-w-[320px]">
                                    <span class="text-primary sm:text-lg leading-none block">More Info</span>
                                    <h4 class="font-medium mt-2 sm:mt-3 text-xl sm:text-2xl leading-none">
                                        {{ $product->extra_title ?: 'Additional Information' }}</h4>
                                    <div class="mt-4 sm:mt-6 sm:text-lg">
                                        {!! $vendorExtraInfo ?: '<p>More details will be shared on request.</p>' !!}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div id="content3" style="display: none;">
                        <div class="product-review-tab-panel review-tab-stack">
@php
    $productReviews = \App\Models\Review::where('product_id', $product->id)
        ->where('status', 'approved')
        ->with('user:id,name')
        ->latest()
        ->get()
        ->map(function ($review) {
            return [
                'id' => $review->id,
                'name' => $review->user->name ?? 'Anonymous',
                'rating' => $review->rating,
                'title' => $review->title ?? \Illuminate\Support\Str::limit((string) $review->comment, 100, ''),
                'comment' => $review->comment,
                'status' => $review->status,
                'review' => $review,
                'created_at' => $review->created_at,
            ];
        });
@endphp
@include('includes.Shop.review', ['reviews' => $productReviews, 'product' => $product])

                            <!-- Review Form -->
                            <div id="review-form" class="review-form review-form-card">
                                <div class="review-form-head">
                                    <div>
                                        <span class="review-form-kicker">Share your experience</span>
                                        <h4 class="review-form-title">Write a Review</h4>
                                        <p class="review-form-copy">Tell other shoppers what stood out for you. Your review will be visible once the admin approves it.</p>
                                    </div>
                                    <p class="review-form-meta">Fields marked with * are required.</p>
                                </div>

                                <form id="reviewForm" class="review-form-grid">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="review-field">
                                        <label class="review-field__label">Name *</label>
                                        <input type="text" name="name" required value="{{ old('name', auth()->user()->name ?? '') }}" class="review-input">
                                    </div>
                                    <div class="review-field">
                                        <label class="review-field__label">Email *</label>
                                        <input type="email" name="email" required value="{{ old('email', auth()->user()->email ?? '') }}" class="review-input">
                                    </div>
                                    <div class="review-field review-field--full">
                                        <label class="review-field__label">Rating *</label>
                                        <div class="review-stars-row">
                                            <div class="review-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="sr-only" required>
                                                <label for="star{{ $i }}" class="star review-star-button" data-rating="{{ $i }}" aria-label="Rate {{ $i }} out of 5">
                                                    <svg viewBox="0 0 15 14" fill="currentColor">
                                                        <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                                                    </svg>
                                                </label>
                                            @endfor
                                            </div>
                                            <span class="review-stars-hint">Tap on a star to set your rating.</span>
                                        </div>
                                    </div>
                                    <div class="review-field review-field--full">
                                        <label class="review-field__label">Your Review *</label>
                                        <textarea name="comment" rows="4" required class="review-textarea" placeholder="What did you like, how was the finish, delivery, comfort, or quality?"></textarea>
                                    </div>
                                    <div class="review-field review-field--full review-submit-row">
                                        <p class="review-submit-note">Your review is sent for moderation before it appears publicly.</p>
                                        <button type="submit" class="review-submit-button">Submit Review</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="content4" style="display: none;">
                        @include('includes.Shop.shipping')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($products->isNotEmpty())
        <div class="s-py-50-100">
            <div class="container-fluid">
                <div class="max-w-[547px] mx-auto text-center">
                    <h6 class="text-2xl sm:text-3xl md:text-4xl leading-none font-bold">Related Products</h6>
                    <p class="mt-3">Explore complementary options that enhance your experience. Discover related products
                        curated just for you.</p>
                </div>
                <div
                    class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-8 md:pt-[50px]">
                    @include('includes.Home.new-products')
                </div>
            </div>
        </div>
    @endif



    @if($isQuotationProduct)
        @include('quotations.form')
    @endif

    @include('includes.footer')
@endsection
