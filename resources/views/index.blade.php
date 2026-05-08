<!-- resources/views/index.blade.php -->
@extends('layouts.main')

@section('title', 'Home')

@section('content')



    <!-- Banner Start -->
    <div class="carousel-slider-four owl-carousel" data-carousel-dots="true" data-carousel-loop="true"
        data-carousel-autoplay="true" data-carousel-smartspeed="900">
        @forelse($homeBanners as $index => $banner)
            @php
                $product = $banner->product;
                $themeIndex = $index % 3;
                $color = $banner->theme_color ?: '#E3B505';
                $price = $product ? ((!is_null($product->sale_price) && $product->sale_price > 0) ? $product->sale_price : $product->price) : null;
                $hasDiscount = $product && !is_null($product->sale_price) && $product->sale_price < $product->price && $product->price > 0;
                $discount = $hasDiscount ? round((1 - ($product->sale_price / $product->price)) * 100) : null;
                $firstImage = $product ? $product->images->first() : null;
                $bannerImg = $banner->image_url ?: ($firstImage ? asset('storage/' . $firstImage->path) : asset('assets/img/home-v1/banner-0' . (($themeIndex % 2) + 1) . '.png'));
                $productLink = $product ? route('product-details', ['slug' => $product->slug]) : route('shop');
                $primaryLink = $banner->button_url ?: $productLink;
                $shopLink = $banner->secondary_button_url ?: route('shop');
                $offerPrice = $banner->offer_price ?: ($price ? currency($price, 0) : null);
                $offerTitle = $banner->offer_title ?: ($product ? Str::limit($product->name, 28) : $banner->title);
                $kicker = $banner->kicker ?: optional($product?->category)->name ?: 'Premium Collection';
            @endphp
            <div class="hero-banner-slide relative overflow-hidden pt-12 md:pt-20 xl:pt-[100px] pb-12 sm:pb-20 xl:pb-24 px-[15px] sm:px-12 dark:bg-title"
                style="background: linear-gradient(135deg, #f9f9f9 0%, #f2e8d5 52%, #fff8eb 100%);">
                <div class="max-w-[1720px] mx-auto relative">
                    <div class="absolute z-0 hidden xl:block shape-01 banner-decor">
                        <svg class="w-[280px] xl:w-[500px] h-[240px] xl:h-[409px]" viewBox="0 0 501 410" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.13"
                                d="M93.5685 350.941C17.9186 286.326 -22.6142 169.412 13.177 95.6561C48.7857 21.5837 161.217 -9.19765 268.179 2.36595C374.958 13.6135 477.265 67.4732 497.265 147.363C516.948 227.436 454.823 333.672 367.72 380.59C280.8 427.824 169.535 415.374 93.5685 350.941Z"
                                fill="{{ $color }}" />
                        </svg>
                    </div>

                    <div class="hero-banner-layout flex items-center justify-between gap-10 flex-col lg:flex-row">
                        <div class="relative z-10 sm:max-w-[632px] w-full slider-content banner-copy">
                            <div class="banner-meta-card" style="border-color: {{ $color }}22;">
                                <p class="uppercase tracking-[0.24em] text-xs font-semibold" style="color: {{ $color }};">
                                    {{ $banner->eyebrow }}</p>
                                @if($offerPrice)
                                    <h4 class="leading-none mt-2 font-semibold text-2xl md:text-3xl" style="color: {{ $color }};">
                                        {{ $offerPrice }}</h4>
                                @endif
                                <p class="mt-2 text-lg md:text-xl font-semibold text-title dark:text-white">
                                    {{ $offerTitle }}</p>
                            </div>

                            <div class="flex items-end content-top banner-title-wrap">
                                <span
                                    class="font-bold text-5xl sm:text-7xl xl:text-9xl text-title leading-none dark:text-white">{{ $banner->season_year }}</span>
                                <span class="banner-season-text">{{ $banner->season_text }}</span>
                            </div>
                            <p class="banner-kicker mt-6 inline-flex items-center text-sm font-medium uppercase tracking-[0.25em]"
                                style="color: {{ $color }};">
                                {{ $kicker }}
                            </p>
                            <h2
                                class="banner-main-title mt-[10px] font-normal text-3xl sm:text-4xl xl:text-5xl !leading-[1.2] ">
                                {{ $banner->title }}</h2>
                            <p class="banner-description dark:text-white-light mt-3 md:mt-4 sm:max-w-[500px] xl:max-w-full">
                                {{ $banner->description }}</p>
                            <div
                                class="button banner-actions banner-actions--desktop mt-4 md:mt-6 flex flex-wrap items-center gap-4">
                                <a class="btn btn-outline" href="{{ $primaryLink }}"
                                    data-text="{{ $banner->button_text }}"><span>{{ $banner->button_text }}</span></a>
                                @if($banner->secondary_button_text)
                                    <a href="{{ $shopLink }}" class="text-title dark:text-white text-underline font-medium">{{ $banner->secondary_button_text }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="relative sm:max-w-[720px] w-full banner-media">
                            @if($hasDiscount)
                                <div class="absolute z-10 hidden md:block banner-discount-badge"
                                    style="--banner-discount-color: {{ $color }};">
                                    <div class="banner-discount-badge__circle">
                                        <h3 class="banner-discount-badge__value">-{{ $discount }}%</h3>
                                        <p class="banner-discount-badge__label">OFF</p>
                                    </div>
                                </div>
                            @endif

                            <div class="w-full p-4 sm:p-6 banner-media-frame"
                               >
                                <img class="slider-img w-full h-auto banner-product-image"
                                    style="max-height: 620px; object-fit: contain;" src="{{ $bannerImg }}"
                                    alt="{{ $offerTitle }}">
                            </div>

                            <div class="button banner-actions banner-actions--mobile mt-4 flex flex-wrap items-center gap-4">
                                <a class="btn btn-outline" href="{{ $primaryLink }}"
                                    data-text="{{ $banner->button_text }}"><span>{{ $banner->button_text }}</span></a>
                                @if($banner->secondary_button_text)
                                    <a href="{{ $shopLink }}" class="text-title dark:text-white text-underline font-medium">{{ $banner->secondary_button_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="relative pt-12 md:pt-20 xl:pt-[100px] pb-12 sm:pb-20 xl:pb-24 px-[15px] sm:px-12 bg-[#F5F5F5] dark:bg-title text-center">
                <p class="text-2xl font-bold text-gray-500">No featured products for banner. Set featured products in admin.</p>
            </div>
        @endforelse
    </div>
    <!-- Banner End -->

    <!-- Product Category Area Start -->
    <div class="home-section home-section--categories s-py-100-50 overflow-hidden">
        <div class="container-fluid">
            <!-- Section Title -->
            <div class="home-section-title max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
               
                <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">Shop by all Category</h3>
                <p class="mt-3">Explore our curated selection of premium products, tailored to suit every need and taste.
                    From essentials to indulgences, find your perfect fit. </p>
            </div>
            <!-- Products Wrapper -->
            <div class="max-w-[1720px] mx-auto" data-aos="fade-up" data-aos-delay="100">
                @include('includes.Home.product-category')
            </div>
        </div>
    </div>
    <!-- Product Category Area End -->

    <!-- New Product Area Start -->
    <div class="home-section home-section--new-products s-py-50-100">
        <div class="container-fluid">
            <!-- Section Title -->
            <div class="home-section-title max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
               
                <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">New Products</h3>
                <p class="mt-3">Be the first to experience innovation with our latest arrivals. Stay ahead of the curve and
                    discover what's new in style, technology, and more. </p>
            </div>
            <div class="max-w-[1720px] mx-auto relative group" data-aos="fade-up" data-aos-delay="100">
                <div class="owl-carousel hv1-new-products-slider" data-carousel-items="4" data-carousel-xl="4"
                    data-carousel-lg="3" data-carousel-md="2" data-carousel-sm="2" data-carousel-xs="2"
                    data-carousel-margin="10" data-carousel-loop="true" data-carousel-autoplay="true">
                    @foreach($products->take(4) ?? [] as $product)
                        @include('includes.Home.new-products', ['product' => $product])
                    @endforeach
                </div>

                <!-- Slider Navigation -->
                <button
                    class="icon hv1newpdct_prev w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary transform p-2 absolute [top:57%] -translate-y-1/2 left-0 z-[999]"
                    aria-label="Prev Navigation">
                    <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.180223 7.38726L5.62434 12.8314C5.8199 13.0598 6.16359 13.0864 6.39195 12.8908C6.62031 12.6952 6.64693 12.3515 6.45132 12.1232C6.43307 12.1019 6.41324 12.082 6.39195 12.0638L1.87877 7.54516L23.4322 7.54516C23.7328 7.54516 23.9766 7.30141 23.9766 7.00072C23.9766 6.70003 23.7328 6.45632 23.4322 6.45632L1.87877 6.45632L6.39195 1.94314C6.62031 1.74758 6.64693 1.40389 6.45132 1.17553C6.25571 0.947171 5.91207 0.920551 5.68371 1.11616C5.66242 1.13441 5.64254 1.15424 5.62434 1.17553L0.180175 6.6197C-0.0308748 6.83196 -0.0308748 7.1749 0.180223 7.38726Z" />
                    </svg>
                </button>
                <button
                    class="icon hv1newpdct_next w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary transform p-2 absolute [top:57%] -translate-y-1/2 right-0 z-[999]"
                    aria-label="Next Navigation">
                    <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" />
                    </svg>
                </button>
            </div>
            <div class="text-center mt-7 md:mt-12">
                <a href="{{ url('/shop') }}" class="btn btn-outline" data-text="All Products">
                    <span>All Products</span>
                </a>
            </div>
        </div>
    </div>
    <!-- New Product Area End -->

    <!-- Choose Area Start -->
    <div class="home-section home-section--services s-py-100 bg-overlay dark:before:bg-title dark:before:bg-opacity-80"
        style="background-image: url('{{ asset('assets/img/home-v1/choose-us-bg.jpg') }}');">
        <img class="absolute top-0 right-0 w-[20%] z-[-1]" src="{{ asset('assets/img/home-v1/shape-01.png') }}" alt="shape">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto">
                <div class="max-w-[1186px] ml-auto">
                    <!-- Section Title -->
                    <!-- <div class="max-w-xl mb-8 md:mb-12" data-aos="fade-up">
                        <div>
                            <svg class="w-14 sm:w-24" viewBox="0 0 64 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M25.7294 12.8432L24.6844 18.9346C24.6552 19.1051 24.6744 19.2804 24.7396 19.4406C24.8048 19.6008 24.9136 19.7395 25.0535 19.8412C25.1935 19.9428 25.3591 20.0033 25.5316 20.0158C25.7041 20.0283 25.8767 19.9922 26.0298 19.9118L31.5002 17.0359L36.97 19.9118C37.1231 19.9922 37.2957 20.0283 37.4682 20.0158C37.6408 20.0033 37.8064 19.9428 37.9463 19.8412C38.0863 19.7395 38.195 19.6008 38.2602 19.4406C38.3255 19.2804 38.3446 19.1051 38.3155 18.9346L37.271 12.8432L41.6969 8.52966C41.8207 8.40881 41.9083 8.25572 41.9498 8.0877C41.9912 7.91968 41.9849 7.74341 41.9314 7.57882C41.8779 7.41423 41.7795 7.26786 41.6472 7.15627C41.5149 7.04468 41.3541 6.97231 41.1828 6.94733L35.0665 6.05918L32.3316 0.517299C32.2552 0.362046 32.1367 0.231305 31.9898 0.13988C31.8429 0.0484557 31.6732 0 31.5002 0C31.3271 0 31.1575 0.0484557 31.0106 0.13988C30.8636 0.231305 30.7452 0.362046 30.6687 0.517299L27.9333 6.05918L21.8175 6.94733C21.6463 6.9722 21.4854 7.04449 21.3531 7.15602C21.2208 7.26755 21.1223 7.41388 21.0688 7.57846C21.0154 7.74303 21.009 7.91929 21.0505 8.08729C21.0919 8.2553 21.1796 8.40835 21.3035 8.52913L25.7294 12.8432ZM28.6826 7.82434C28.8313 7.8026 28.9725 7.74509 29.094 7.65676C29.2156 7.56843 29.3139 7.45191 29.3805 7.31721L31.5002 3.02487L33.6199 7.31721C33.6865 7.45191 33.7848 7.56843 33.9063 7.65676C34.0279 7.74509 34.1691 7.8026 34.3178 7.82434L39.0573 8.51323L35.6277 11.8554C35.5203 11.9605 35.4401 12.0902 35.3939 12.2331C35.3478 12.3761 35.3371 12.5282 35.3628 12.6763L36.1719 17.3957L31.9326 15.1701C31.7995 15.1001 31.6514 15.0636 31.501 15.0636C31.3506 15.0636 31.2025 15.1001 31.0694 15.1701L26.83 17.3957L27.6397 12.6763C27.6653 12.5282 27.6545 12.3762 27.6084 12.2332C27.5622 12.0902 27.4821 11.9606 27.3748 11.8554L23.9457 8.51323L28.6826 7.82434Z"
                                    fill="#BB976D" />
                                <path
                                    d="M62.9545 9.72055C62.901 9.55607 62.8026 9.40981 62.6704 9.29829C62.5382 9.18677 62.3774 9.11443 62.2063 9.08942L57.349 8.38357L55.1764 3.98525C55.0997 3.83018 54.9812 3.69965 54.8342 3.60838C54.6872 3.51711 54.5177 3.46875 54.3447 3.46875C54.1717 3.46875 54.0021 3.51711 53.8552 3.60838C53.7082 3.69965 53.5897 3.83018 53.513 3.98525L51.3403 8.38622L46.4831 9.09207C46.3119 9.11702 46.1511 9.18937 46.0188 9.30094C45.8866 9.41251 45.7882 9.55884 45.7348 9.7234C45.6813 9.88796 45.675 10.0642 45.7165 10.2322C45.758 10.4001 45.8457 10.5531 45.9696 10.6739L49.484 14.0998L48.6547 18.9374C48.6255 19.1079 48.6447 19.2832 48.7099 19.4434C48.7751 19.6036 48.8839 19.7424 49.0238 19.844C49.1638 19.9457 49.3294 20.0061 49.5019 20.0186C49.6744 20.0311 49.847 19.9951 50.0001 19.9146L54.3455 17.6306L58.6908 19.9146C58.8439 19.9951 59.0165 20.0311 59.189 20.0186C59.3616 20.0061 59.5272 19.9457 59.6671 19.844C59.8071 19.7424 59.9158 19.6036 59.981 19.4434C60.0463 19.2832 60.0654 19.1079 60.0363 18.9374L59.2064 14.0998L62.7214 10.6739C62.8456 10.5527 62.9333 10.3991 62.9745 10.2306C63.0157 10.0621 63.0088 9.88535 62.9545 9.72055ZM57.5621 13.112C57.4547 13.2171 57.3744 13.3468 57.3283 13.4898C57.2821 13.6327 57.2714 13.7848 57.2971 13.9329L57.8912 17.3985L54.7774 15.7616C54.6442 15.6917 54.4961 15.6551 54.3457 15.6551C54.1954 15.6551 54.0472 15.6917 53.9141 15.7616L50.8014 17.398L51.3959 13.9323C51.4216 13.7843 51.4109 13.6322 51.3648 13.4892C51.3186 13.3462 51.2384 13.2166 51.131 13.1115L48.6123 10.6569L52.0923 10.1514C52.241 10.1297 52.3823 10.0722 52.504 9.98391C52.6256 9.89557 52.724 9.77901 52.7907 9.64425L54.3471 6.4907L55.9034 9.64425C55.97 9.77895 56.0683 9.89546 56.1899 9.9838C56.3115 10.0721 56.4526 10.1296 56.6013 10.1514L60.0818 10.6569L57.5621 13.112Z"
                                    fill="#BB976D" />
                                <path
                                    d="M13.4299 20.0176C13.565 20.0177 13.6984 19.9883 13.8209 19.9314C13.9434 19.8745 14.0519 19.7916 14.139 19.6884C14.2261 19.5851 14.2895 19.4641 14.325 19.3338C14.3604 19.2035 14.3669 19.067 14.344 18.9339L13.5147 14.0963L17.0296 10.6704C17.1536 10.5496 17.2412 10.3965 17.2827 10.2285C17.3242 10.0605 17.3178 9.88428 17.2643 9.71971C17.2108 9.55513 17.1124 9.4088 16.9801 9.29727C16.8477 9.18573 16.6869 9.11344 16.5156 9.08858L11.6584 8.38272L9.48573 3.9844C9.40912 3.82932 9.29065 3.69875 9.14373 3.60745C8.9968 3.51615 8.82727 3.46777 8.65429 3.46777C8.48131 3.46777 8.31177 3.51615 8.16485 3.60745C8.01792 3.69875 7.89946 3.82932 7.82285 3.9844L5.65018 8.38537L0.794542 9.08858C0.623248 9.11336 0.462309 9.18559 0.329933 9.29709C0.197556 9.4086 0.0990246 9.55492 0.045485 9.71951C-0.00805467 9.88409 -0.0144656 10.0604 0.0269773 10.2284C0.0684203 10.3965 0.156063 10.5496 0.279991 10.6704L3.79494 14.0963L2.96509 18.9339C2.93594 19.1044 2.95507 19.2797 3.0203 19.4399C3.08553 19.6001 3.19427 19.7389 3.33423 19.8405C3.47419 19.9422 3.63979 20.0026 3.81232 20.0151C3.98485 20.0276 4.15742 19.9916 4.31055 19.9111L8.65588 17.6271L13.0012 19.9111C13.1334 19.9807 13.2805 20.0173 13.4299 20.0176ZM9.08458 15.7608C8.95146 15.6908 8.80334 15.6543 8.65296 15.6543C8.50259 15.6543 8.35446 15.6908 8.22134 15.7608L5.11019 17.395L5.70476 13.9294C5.73031 13.7813 5.71954 13.6293 5.67339 13.4863C5.62724 13.3433 5.54709 13.2137 5.4398 13.1085L2.92163 10.654L6.40107 10.1484C6.54988 10.1268 6.69122 10.0694 6.81289 9.98106C6.93456 9.89271 7.03293 9.7761 7.09951 9.64128L8.65482 6.48721L10.2112 9.64075C10.2778 9.77557 10.3761 9.89218 10.4978 9.98053C10.6195 10.0689 10.7608 10.1263 10.9096 10.1479L14.3891 10.6534L11.8709 13.1112C11.7635 13.2163 11.6832 13.3459 11.637 13.4889C11.5909 13.6319 11.5802 13.784 11.6059 13.932L12.2 17.3977L9.08458 15.7608Z"
                                    fill="#BB976D" />
                                <path
                                    d="M50.9001 42.2896C50.8989 41.2945 50.5031 40.3404 49.7995 39.6368C49.0958 38.9331 48.1418 38.5373 47.1466 38.5362H35.7099V26.3322C35.7099 25.2159 35.2665 24.1452 34.4771 23.3559C33.6877 22.5665 32.6171 22.123 31.5008 22.123C30.3845 22.123 29.3139 22.5665 28.5245 23.3559C27.7351 24.1452 27.2917 25.2159 27.2917 26.3322V28.6585C27.2945 29.9931 26.869 31.2933 26.0776 32.368L21.5527 38.5362H13.0289C12.783 38.5362 12.5471 38.6339 12.3732 38.8078C12.1993 38.9817 12.1016 39.2176 12.1016 39.4636V62.0725C12.1016 62.3185 12.1993 62.5543 12.3732 62.7283C12.5471 62.9022 12.783 62.9999 13.0289 62.9999H45.5569C46.2926 63.0002 47.0122 62.7843 47.6262 62.3788C48.2401 61.9734 48.7213 61.3964 49.0099 60.7196C49.2984 60.0429 49.3817 59.2962 49.2492 58.5725C49.1167 57.8488 48.7744 57.18 48.2648 56.6493C48.7037 56.337 49.0702 55.9337 49.3392 55.467C49.6082 55.0002 49.7735 54.481 49.8238 53.9446C49.8741 53.4082 49.8082 52.8673 49.6306 52.3587C49.453 51.8501 49.1679 51.3857 48.7947 50.9972C49.2336 50.6849 49.6001 50.2816 49.8691 49.8149C50.1382 49.3481 50.3035 48.8289 50.3538 48.2925C50.404 47.7561 50.3381 47.2152 50.1605 46.7066C49.983 46.198 49.6978 45.7336 49.3246 45.3451C49.8118 44.9983 50.2089 44.54 50.483 44.0085C50.7571 43.477 50.9 42.8876 50.9001 42.2896ZM13.9563 40.3909H21.0953V61.1452H13.9563V40.3909ZM47.1466 44.1883H46.6167C46.3708 44.1883 46.1349 44.286 45.961 44.4599C45.7871 44.6338 45.6894 44.8697 45.6894 45.1157C45.6894 45.3616 45.7871 45.5975 45.961 45.7714C46.1349 45.9453 46.3708 46.043 46.6167 46.043C47.1149 46.0513 47.5898 46.2551 47.9391 46.6103C48.2885 46.9655 48.4842 47.4438 48.4842 47.942C48.4842 48.4402 48.2885 48.9185 47.9391 49.2737C47.5898 49.6289 47.1149 49.8326 46.6167 49.8409H46.0868C45.8409 49.8409 45.605 49.9387 45.4311 50.1126C45.2571 50.2865 45.1594 50.5224 45.1594 50.7683C45.1594 51.0143 45.2571 51.2501 45.4311 51.424C45.605 51.598 45.8409 51.6957 46.0868 51.6957C46.5904 51.6957 47.0733 51.8957 47.4294 52.2518C47.7855 52.6078 47.9855 53.0908 47.9855 53.5944C47.9855 54.0979 47.7855 54.5809 47.4294 54.9369C47.0733 55.293 46.5904 55.4931 46.0868 55.4931H45.5569C45.3109 55.4931 45.0751 55.5908 44.9011 55.7647C44.7272 55.9386 44.6295 56.1745 44.6295 56.4204C44.6295 56.6664 44.7272 56.9022 44.9011 57.0761C45.0751 57.2501 45.3109 57.3478 45.5569 57.3478C46.0605 57.3478 46.5434 57.5478 46.8995 57.9039C47.2555 58.26 47.4556 58.7429 47.4556 59.2465C47.4556 59.75 47.2555 60.233 46.8995 60.589C46.5434 60.9451 46.0605 61.1452 45.5569 61.1452H22.95V39.7667L27.5731 33.4633C28.5984 32.0712 29.1498 30.3869 29.1464 28.658V26.3317C29.1464 25.7072 29.3944 25.1084 29.836 24.6668C30.2775 24.2253 30.8764 23.9772 31.5008 23.9772C32.1252 23.9772 32.7241 24.2253 33.1656 24.6668C33.6072 25.1084 33.8552 25.7072 33.8552 26.3317V39.463C33.8552 39.709 33.9529 39.9449 34.1269 40.1188C34.3008 40.2927 34.5366 40.3904 34.7826 40.3904H47.1466C47.6502 40.3904 48.1331 40.5904 48.4892 40.9465C48.8453 41.3026 49.0453 41.7855 49.0453 42.2891C49.0453 42.7926 48.8453 43.2756 48.4892 43.6317C48.1331 43.9877 47.6502 44.1878 47.1466 44.1878V44.1883Z"
                                    fill="#BB976D" />
                            </svg>
                        </div>
                        <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">Why you Choose Us</h3>
                        <p class="mt-3">Choose us for unparalleled quality, exceptional service, and a commitment to your
                            satisfaction. Join countless others who rely on us for reliability. </p>
                    </div> -->
                    <!-- Chose Wrapper -->
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-[30px]">

                        <!-- includes/Home/services.blade.php -->
                        @include('includes.Home.services')

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Choose Area End -->

    <!-- Feature Post Start -->
    <div class="home-section home-section--featured s-py-100-50">
        <div class="container-fluid">
            <!-- Section Title -->
            <div class="home-section-title max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
               
                <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">Featured Products</h3>
                <p class="mt-3">Discover our handpicked selection of standout products. Elevate your lifestyle with our top
                    picks that combine quality, style, and innovation. </p>
            </div>
            <!-- Feature Product Wrapper -->
            <div class="max-w-[1720px] mx-auto" data-aos="fade-up"
                data-aos-delay="100">

                <!-- includes/Home/featured-products.blade.php -->
                @include('includes.Home.featured-products')

            </div>
        </div>
    </div>
    <!-- Feature Post End -->



    <!-- Customer Reviews Start -->
    <section class="product-customer-say home-customer-say s-py-50">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto" data-aos="fade-up" data-aos-delay="100">
                <div class="product-section-heading text-center">
                    <h3>Customers Say!</h3>
                    <p>Real feedback from verified customers.</p>
                </div>

                <div class="product-carousel-shell mt-8" data-simple-carousel data-autoplay="true">
                    <button type="button" class="product-carousel-arrow product-carousel-arrow--prev" data-carousel-prev aria-label="Previous customer review">&#8249;</button>
                    <div class="product-carousel-track product-review-strip" data-carousel-track>
                        @include('includes.Home.testimonial')
                    </div>
                    <button type="button" class="product-carousel-arrow product-carousel-arrow--next" data-carousel-next aria-label="Next customer review">&#8250;</button>
                </div>
            </div>
        </div>
    </section>
    <!-- Customer Reviews End -->



    @include('includes.footer')

@endsection
