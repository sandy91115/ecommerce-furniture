<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<<<<<<< HEAD
    @include('seo.meta')
=======
    <title>@yield('title', 'Furniture Store - Premium Home Decor')</title>
    <meta name="description"
        content="@yield('meta_description', 'Furniture Store - Premium Home Decor. Read customer reviews and testimonials for our stylish and high-quality furniture.')">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    @php
        $siteFaviconPath = \App\Models\Setting::get('site_favicon_path');
        $faviconUrl = $siteFaviconPath ? asset('storage/' . $siteFaviconPath) : asset('assets/img/favicon.png');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">

<<<<<<< HEAD
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('head')

    <!-- Main Stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{ versioned_asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('assets/css/product-ui-fixes.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('assets/css/button-override.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
=======
    <!-- Meta tags for SEO -->
    <meta
        content="	ceramics, decoration, ecommerce, ecommerce template, elementor, furniture, furniture store, furniture template, interior design, interior design template, shopping, simple ecommerce, store, store template"
        name="keywords">
    <meta name="author" content="Lets Digital Marketing">
    <meta name="website" content="https://www.letsdigitalmarketing.com/">
    <meta name="email" content="support@shreethemes.in">
    <meta name="version" content="1.0.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Reviews Aggregate JSON-LD for SEO -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "Organization",
      "name": "Furnixar",
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ number_format($testimonials->avg('rating') ?? 4.8, 1) }}",
        "reviewCount": "{{ $testimonials->count() }}",
        "bestRating": "5",
        "worstRating": "1"
      },
      "review": [
        @foreach($testimonials->take(5) as $review)
            {
              "@type": "Review",
              "author": { "@type": "Person", "name": "{{ addslashes($review->user->name ?? 'Anonymous') }}" },
              "reviewRating": {
                "@type": "Rating",
                "ratingValue": "{{ $review->rating }}",
                "bestRating": "5",
                "worstRating": "1"
              },
              "reviewBody": "{{ addslashes($review->title ?? $review->comment ?? '') }}"
            }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
    </script>

    @stack('head')

    <!-- Main Stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/product-ui-fixes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/button-override.css') }}">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <!-- Swiper CSS for product lightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
</head>

<body class="dark:bg-title">



    @section('topbar')

    @show

    @section('navbar')
    @include('includes.navbar')
    @show

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

<<<<<<< HEAD
    <!-- Back to top control -->
=======
    <!-- Back to top -->
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <a href="#" onclick="topFunction()" id="back-to-top"
        class="back-to-top site-back-to-top fixed hidden z-10 bottom-5 right-5 h-11 w-11 rounded-full text-center leading-[44px]"
        aria-label="Back to top">
        <i class="mdi mdi-arrow-up text-xl"></i>
    </a>
<<<<<<< HEAD

    <!-- Product image lightbox modal -->
    <div id="product-lightbox-modal"
        class="product-lightbox-modal fixed inset-0 bg-black bg-opacity-90 z-[9999] hidden flex items-center justify-center p-4">
        <div class="lightbox-container w-full h-full relative max-w-6xl max-h-[90vh] mx-auto">
            <!-- Close button -->
=======
    <!-- Back to top -->

    <!-- Product Image Lightbox Modal -->
    <div id="product-lightbox-modal"
        class="product-lightbox-modal fixed inset-0 bg-black bg-opacity-90 z-[9999] hidden flex items-center justify-center p-4">
        <div class="lightbox-container w-full h-full relative max-w-6xl max-h-[90vh] mx-auto">
            <!-- Close Button -->
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            <button id="lightbox-close"
                class="absolute top-6 right-6 z-10 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white text-2xl backdrop-blur-sm transition-all duration-300">
                <i class="mdi mdi-close"></i>
            </button>

<<<<<<< HEAD
            <!-- Lightbox gallery -->
=======
            <!-- Swiper Gallery -->
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            <div class="swiper product-lightbox-swiper h-full w-full">
                <div class="swiper-wrapper"></div>
                <!-- Navigation -->
                <div
                    class="swiper-button-prev absolute left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white text-xl backdrop-blur-sm transition-all duration-300 opacity-0 group-hover:opacity-100">
                </div>
                <div
                    class="swiper-button-next absolute right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white text-xl backdrop-blur-sm transition-all duration-300 opacity-0 group-hover:opacity-100">
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex gap-2"></div>
            </div>

<<<<<<< HEAD
            <!-- Product metadata overlay -->
=======
            <!-- Product Info Overlay (optional, bottom bar like Flipkart) -->
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            <div
                class="lightbox-info absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 text-white pointer-events-none">
                <div id="lightbox-product-name" class="text-lg font-semibold mb-1"></div>
                <div id="lightbox-product-price" class="text-sm opacity-90"></div>
            </div>
        </div>
    </div>

<<<<<<< HEAD
    <!-- WhatsApp floating CTA -->
=======
    <!-- WhatsApp Floating Button -->
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    @php
        $rawWhatsappNumber = $whatsappNumber ?? \App\Models\Setting::get('whatsapp_number', '1234567890');
        $whatsappNumberForLink = preg_replace('/\D+/', '', (string) $rawWhatsappNumber);
        $whatsappMessage = rawurlencode("Hi! I'm interested in your furniture");
    @endphp
    <a href="https://wa.me/{{ $whatsappNumberForLink ?: '1234567890' }}?text={{ $whatsappMessage }}" target="_blank"
        rel="noopener noreferrer" class="whatsapp-float group" title="Chat on WhatsApp" aria-label="Chat on WhatsApp">
        <span class="whatsapp-float__icon" aria-hidden="true">
            <i class="mdi mdi-whatsapp"></i>
        </span>
        <svg class="w-7 h-7 group-hover:scale-110 transition-transform duration-300" fill="currentColor"
            viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1 -5.031 -1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1 -1.371 -5.05c.584-.815 1.51-1.481 2.666-2.2l.304-.22 3.472 1.022.419-.936c.775-.957 1.777-1.7 2.977-2.237a9.845 9.845 0 0 1 5.017 1.371l.361.214 3.741-.982-.998 3.648.235.374a9.86 0 0 0 1.371 5.05c-.584.815-1.51 1.481-2.666 2.2l-.304.22-3.472-1.022-.419.936c-.775.957-1.777 1.7-2.977 2.237a9.845 9.845 0 0 1 -5.019 -1.371z" />
        </svg>
        <span class="whatsapp-float__tooltip">Chat with us</span>
    </a>
<<<<<<< HEAD

    <script src="{{ versioned_asset('assets/js/scripts.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/base.js') }}"></script>
    
    <script src="{{ versioned_asset('assets/js/cart-functions.js') }}"></script>
=======
    <!-- WhatsApp Floating Button -->

    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/base.js') }}"></script>
    
    <script src="{{ asset('assets/js/cart-functions.js') }}"></script>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    
    <script>
    window.Laravel = window.Laravel || {};
    window.Laravel.cart = {
        removeUrl: '/cart/remove',
        updateUrl: '/cart/update',
        csrfToken: '{{ csrf_token() }}'
    };
    </script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Product Lightbox JS -->
<<<<<<< HEAD
    <script src="{{ versioned_asset('assets/js/product-lightbox.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/wishlist.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/quote-modal.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/custom.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/review-form.js') }}"></script>
=======
    <script src="{{ asset('assets/js/product-lightbox.js') }}"></script>
    <script src="{{ asset('assets/js/wishlist.js') }}"></script>
    <script src="{{ asset('assets/js/quote-modal.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/review-form.js') }}"></script>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    @stack('scripts')
</body>

</html>
