@extends('layouts.main')

@section('title', 'Shop')

@section('content')


    <!-- Banner Start -->
    <div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70"
        style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
        <div class="text-center w-full">
            <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Shop</h2>
            <ul
                class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>/</li>
                <li class="text-primary">Shop</li>
            </ul>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Shop Slider Start -->
    <!-- <div class="s-py-100-50 overflow-hidden" data-aos="fade-up">
        <div class="relative">
            <button class="absolute top-[56%] -translate-y-1/2 left-0 z-10 shop_prev w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-white duration-300 bg-title bg-opacity-90 hover:bg-primary p-2">
                <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.180223 7.38726L5.62434 12.8314C5.8199 13.0598 6.16359 13.0864 6.39195 12.8908C6.62031 12.6952 6.64693 12.3515 6.45132 12.1232C6.43307 12.1019 6.41324 12.082 6.39195 12.0638L1.87877 7.54516L23.4322 7.54516C23.7328 7.54516 23.9766 7.30141 23.9766 7.00072C23.9766 6.70003 23.7328 6.45632 23.4322 6.45632L1.87877 6.45632L6.39195 1.94314C6.62031 1.74758 6.64693 1.40389 6.45132 1.17553C6.25571 0.947171 5.91207 0.920551 5.68371 1.11616C5.66242 1.13441 5.64254 1.15424 5.62434 1.17553L0.180175 6.6197C-0.0308748 6.83196 -0.0308748 7.1749 0.180223 7.38726Z"/>
                </svg>
            </button>
            <button class="absolute top-[56%] -translate-y-1/2 z-10 right-0 shop_next w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-white duration-300 bg-title bg-opacity-90 hover:bg-primary p-2">
                <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z"/>
                </svg>
            </button>
            <div class="container">
                <div class="owl-carousel shop-v3-slider max-w-[1440px] mx-auto" data-carousel-autoplay="true" data-carousel-loop="true" data-carousel-animateout="false" data-carousel-margin="0" data-carousel-items="3" data-carousel-xl="3" data-carousel-lg="3" data-carousel-md="2" data-carousel-sm="2">
                    @include('includes.Shop.shop-slider')
                </div>
            </div>
        </div>
    </div> -->
    <!-- Shop Slider End -->

    <!-- Filter Form & Products Start -->
    <div class="s-py-50-100" data-aos="fade-up">
        <div class="container-fluid">
            @php
                $currencySymbol = currency_symbol();
                $minPriceValue = request()->filled('min_price')
                    ? number_format((float) request('min_price'), 2, '.', '')
                    : '';
                $maxPriceValue = request()->filled('max_price')
                    ? number_format((float) request('max_price'), 2, '.', '')
                    : '';
                $minPricePlaceholder = number_format((float) $minPrice, 2, '.', '');
                $maxPricePlaceholder = number_format((float) $maxPrice, 2, '.', '');
            @endphp
            <!-- Top Filter Form -->
            <form method="GET" action="{{ request()->url() }}"
                class="shop-filter-toolbar flex flex-col lg:flex-row items-start lg:items-center lg:justify-center gap-6 flex-wrap mb-[15px]"
                data-shop-filter-form>
                <!-- Category Select -->
                <div
                    class="flex items-start sm:items-center gap-[25px] flex-wrap sm:flex-nowrap sm:max-w-[420px] w-full flex-col sm:flex-row">
                    <h4 class="font-medium leading-none text-xl flex-none">Category</h4>
                    <select name="category" class="sm:max-w-[252px] w-full outline-select small-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->products_count }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort By -->
                <div
                    class="flex items-start sm:items-center gap-[15px] flex-wrap sm:flex-nowrap sm:max-w-[347px] w-full flex-col sm:flex-row">
                    <h4 class="font-medium leading-none text-xl flex-none">Sort By</h4>
                    <select name="sort_by" class="sm:max-w-[252px] w-full outline-select small-select">
                        <option value="latest" {{ request('sort_by', 'latest') == 'latest' ? 'selected' : '' }}>Latest
                        </option>
                        <option value="price-asc" {{ request('sort_by') == 'price-asc' ? 'selected' : '' }}>Price Low to High
                        </option>
                        <option value="price-desc" {{ request('sort_by') == 'price-desc' ? 'selected' : '' }}>Price High to
                            Low</option>
                        <option value="name-asc" {{ request('sort_by') == 'name-asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name-desc" {{ request('sort_by') == 'name-desc' ? 'selected' : '' }}>Name Z-A</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div
                    class="flex items-start sm:items-center gap-[15px] flex-wrap sm:flex-nowrap sm:max-w-[411px] w-full flex-col sm:flex-row">
                    <h4 class="font-medium leading-none text-xl flex-none">Price Range</h4>
                    <div class="shop-price-range grid grid-cols-2 gap-[12px] sm:max-w-[260px] w-full">
                        <label for="min_price" class="shop-price-field border border-title dark:border-white-light">
                            <span class="shop-price-field__tag">Min</span>
                            <span class="shop-price-field__currency">{{ $currencySymbol }}</span>
                            <input id="min_price" name="min_price" class="shop-price-field__input" data-price-field
                                type="number" inputmode="decimal" min="0" step="0.01" value="{{ $minPriceValue }}"
                                placeholder="{{ $minPricePlaceholder }}" autocomplete="off">
                        </label>
                        <label for="max_price" class="shop-price-field border border-title dark:border-white-light">
                            <span class="shop-price-field__tag">Max</span>
                            <span class="shop-price-field__currency">{{ $currencySymbol }}</span>
                            <input id="max_price" name="max_price" class="shop-price-field__input" data-price-field
                                type="number" inputmode="decimal" min="0" step="0.01" value="{{ $maxPriceValue }}"
                                placeholder="{{ $maxPricePlaceholder }}" autocomplete="off">
                        </label>
                    </div>
                </div>

                <button type="submit" class="shop-filter-submit btn btn-solid px-8 min-w-[140px]" data-text="Filter">
                    <span>Filter</span>
                </button>
            </form>

            <!-- Products Grid -->
            <div class="max-w-[1720px] mx-auto">
                <div id="products-grid"
                    class="shop-product-grid grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 lg:gap-[30px]">
                    @include('includes.Shop.shops-v3', ['products' => $products])
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="flex justify-center mt-12">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->

    @include('includes.footer')
    @push('scripts')
        <script src="{{ asset('assets/js/shop-filters.js') }}"></script>
    @endpush
@endsection