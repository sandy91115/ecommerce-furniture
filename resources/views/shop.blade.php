@extends('layouts.main')

@php
    $selectedCategory = $selectedCategory ?? null;
    $selectedCategorySlug = $selectedCategorySlug ?? request()->route('category')?->slug ?? request('category');
    $breadcrumbCategories = $breadcrumbCategories ?? collect();
    $categories = $categories ?? collect();
    $minPrice = $minPrice ?? 0;
    $maxPrice = $maxPrice ?? 1000;
    $pageTitle = $pageTitle ?? ($selectedCategory?->name ?: 'Shop');
    $pageDescription = $pageDescription ?? 'Explore our premium furniture collection.';
    $canonicalUrl = $canonicalUrl ?? ($selectedCategory ? route('shop.category', ['category' => $selectedCategory]) : route('shop'));
    $pageHeading = $selectedCategory?->name ?: 'Shop';
@endphp

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('content')


    <!-- Banner Start -->
    <div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70"
        style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
        <div class="text-center w-full">
            <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">{{ $pageHeading }}</h2>
            <ul
                class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>/</li>
                @if($breadcrumbCategories->isNotEmpty())
                    <li><a href="{{ route('shop') }}">Shop</a></li>
                    @foreach($breadcrumbCategories as $breadcrumbCategory)
                        <li>/</li>
                        @if($loop->last)
                            <li class="text-primary">{{ $breadcrumbCategory->name }}</li>
                        @else
                            <li><a href="{{ route('shop.category', ['category' => $breadcrumbCategory->slug]) }}">{{ $breadcrumbCategory->name }}</a></li>
                        @endif
                    @endforeach
                @else
                    <li class="text-primary">Shop</li>
                @endif
            </ul>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Filter Form & Products Start -->
    <div class="shop-listing-section s-py-50-100" data-aos="fade-up">
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
                $minPricePlaceholderText = 'Min ' . $currencySymbol . ' ' . $minPricePlaceholder;
                $maxPricePlaceholderText = 'Max ' . $currencySymbol . ' ' . $maxPricePlaceholder;
            @endphp
            <!-- Top Filter Form -->
            <form method="GET" action="{{ route('shop') }}"
                class="shop-filter-toolbar"
                data-shop-filter-form
                data-shop-url="{{ route('shop') }}"
                data-category-base-url="{{ url('/category') }}"
                data-current-category="{{ $selectedCategorySlug }}">
                <!-- Category Select -->
                <div class="shop-filter-control shop-filter-control--category">
                    <label class="shop-filter-label" for="shop_category">Category</label>
                    <select id="shop_category" name="category" class="shop-filter-select outline-select small-select" data-category-filter>
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ $selectedCategorySlug == $category->slug ? 'selected' : '' }}>
                                {{ $category->parent ? $category->parent->name . ' / ' : '' }}{{ $category->name }} ({{ $category->products_count }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort By -->
                <div class="shop-filter-control">
                    <label class="shop-filter-label" for="shop_sort">Sort By</label>
                    <select id="shop_sort" name="sort_by" class="shop-filter-select outline-select small-select">
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
                <div class="shop-filter-control shop-filter-control--price">
                    <span class="shop-filter-label">Price Range</span>
                    <div class="shop-price-range grid grid-cols-2 gap-[16px] sm:max-w-[290px] w-full">
                        <label for="min_price" class="shop-price-field">
                            <input id="min_price" name="min_price" class="shop-price-field__input" data-price-field
                                type="number" inputmode="decimal" min="0" step="0.01" value="{{ $minPriceValue }}"
                                placeholder="{{ $minPricePlaceholderText }}" autocomplete="off"
                                aria-label="Minimum price">
                        </label>
                        <label for="max_price" class="shop-price-field">
                            <input id="max_price" name="max_price" class="shop-price-field__input" data-price-field
                                type="number" inputmode="decimal" min="0" step="0.01" value="{{ $maxPriceValue }}"
                                placeholder="{{ $maxPricePlaceholderText }}" autocomplete="off"
                                aria-label="Maximum price">
                        </label>
                    </div>
                </div>

                <button type="submit" class="shop-filter-submit btn btn-solid px-8 min-w-[140px]" data-text="Filter">
                    <span>Filter</span>
                </button>
            </form>

            <!-- Products Grid -->
            <div class="shop-results-wrap max-w-[1720px] mx-auto">
                <div id="products-grid"
                    class="shop-product-grid">
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
        <script src="{{ versioned_asset('assets/js/shop-filters.js') }}"></script>
    @endpush
@endsection
