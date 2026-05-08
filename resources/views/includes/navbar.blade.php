@php
    $navCategories = \App\Models\Category::query()
        ->where('status', 'active')
        ->whereNull('parent_id')
        ->withCount([
            'products as active_products_count' => fn($query) => $query->where('status', 'active'),
        ])
        ->with([
            'children' => fn($query) => $query
                ->where('status', 'active')
                ->withCount([
                    'products as active_products_count' => fn($productQuery) => $productQuery->where('status', 'active'),
                ])
                ->with([
                    'children' => fn($childQuery) => $childQuery
                        ->where('status', 'active')
                        ->withCount([
                            'products as active_products_count' => fn($productQuery) => $productQuery->where('status', 'active'),
                        ])
                        ->orderBy('name'),
                ])
                ->orderBy('name'),
        ])
        ->orderBy('name')
        ->get();
    $selectedCategorySlug = request()->route('category')?->slug ?? request('category');
    $totalProducts = \App\Models\Product::where('status', 'active')->count();
    $headerMenus = collect($menus['header_main'] ?? []);
    if ($headerMenus->isEmpty()) {
        $headerMenus = collect([
            (object) ['title' => 'Home', 'url' => '/', 'children' => collect()],
            (object) ['title' => 'Shop', 'url' => '/shop', 'children' => collect()],
            (object) ['title' => 'About Us', 'url' => '/about', 'children' => collect()],
            (object) ['title' => 'Contact', 'url' => '/contact', 'children' => collect()],
        ]);
    }
    $menuUrl = function ($url) {
        $url = filled($url) ? $url : '#';

        return \Illuminate\Support\Str::startsWith($url, ['http://', 'https://', '#'])
            ? $url
            : url($url);
    };
    $isMenuActive = function ($url) {
        if (blank($url) || \Illuminate\Support\Str::startsWith($url, '#')) {
            return false;
        }

        $path = '/' . trim(parse_url($url, PHP_URL_PATH) ?? $url, '/');

        return request()->is(trim($path, '/') ?: '/') || request()->path() === trim($path, '/');
    };
@endphp

<!-- Header Start -->
<div class="header-area default-header sticky top-0 z-50 bg-white dark:bg-title shadow-sm border-b border-bdr-clr dark:border-bdr-clr-drk transition-all duration-300">
    <div class="container-fluid">
        <div class="site-header-row flex items-center justify-between gap-x-3 max-w-[1720px] mx-auto relative py-[10px] sm:py-4 lg:py-0">
            <!-- Logo -->
            <a class="site-header-brand cursor-pointer block" href="{{ url('/') }}" aria-label="CAROM STUDIOS">
                @php
                    $siteLogoPath = \App\Models\Setting::get('site_logo_path');
                    $logoUrl = $siteLogoPath ? asset('storage/' . $siteLogoPath) : asset('assets/img/footer-logo.svg');
                @endphp
                <img class="nav-logo fill-current dark:text-white text-title w-[104px] sm:w-[160px] object-contain" src="{{ $logoUrl }}" alt="CAROM STUDIOS" onerror="this.onerror=null; this.src='{{ asset('assets/img/footer-logo.svg') }}';">
            </a>

            <!-- Header Right -->
            <div class="site-header-actions flex items-center gap-4 sm:gap-6">
                @auth
                <div class="relative group">
                    <a href="/my-profile" class="site-header-auth-link site-header-auth-link--mobile lg:hidden" aria-label="My account">
                        <i class="mdi mdi-account-outline" aria-hidden="true"></i>
                        <span>Account</span>
                    </a>
                    <a href="/my-profile" class="text-lg leading-none text-title dark:text-white transition-all duration-300 hover:text-primary hidden lg:block">
                        Hi, {{ auth()->user()->name }}
                        <i class="fas fa-chevron-down ml-1"></i>
                    </a>
                    <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-title shadow-lg rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                        <a href="/my-profile" class="block px-4 py-2 text-sm text-gray-700  hover:bg-gray-100 dark:hover:bg-gray-800">My Account</a>
                        <a href="/order-history" class="block px-4 py-2 text-sm text-gray-700  hover:bg-gray-100 dark:hover:bg-gray-800">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700  hover:bg-gray-100 dark:hover:bg-gray-800">Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="site-header-auth-link site-header-auth-link--mobile lg:hidden" aria-label="Login or signup">
                    <i class="mdi mdi-account-outline" aria-hidden="true"></i>
                    <span>Login/Signup</span>
                </a>
                <a href="{{ route('login') }}" class="text-lg leading-none text-title dark:text-white transition-all duration-300 hover:text-primary hidden lg:block">Login/Signup</a>
                @endauth


                <!-- WishList -->
             

               
                <!-- Cart -->
                <div class="relative group">
                    <button class="relative hdr_cart_btn">
                        <span class="cart-count absolute w-[22px] h-[22px] bg-secondary -top-[10px] -right-[11px] rounded-full flex items-center justify-center text-xs leading-none text-white">{{ $cartCount }}</span>
                        <svg class="fill-current text-title dark:text-white w-[18px] sm:w-[19px]" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.8284 5.7238H15.2408C14.9035 2.85886 12.4608 0.628906 9.50675 0.628906C6.55269 0.628906 4.11002 2.85886 3.7727 5.7238H1.18509C0.716102 5.7238 0.335938 6.10397 0.335938 6.57295V21.518C0.335938 21.987 0.716102 22.3671 1.18509 22.3671H17.8284C18.2974 22.3671 18.6776 21.987 18.6776 21.518V6.57295C18.6776 6.10397 18.2974 5.7238 17.8284 5.7238ZM9.50675 2.3272C11.5228 2.3272 13.2014 3.79857 13.5257 5.7238H5.48777C5.81214 3.79857 7.4907 2.3272 9.50675 2.3272ZM16.9793 20.6688H2.03424V7.4221H3.73253V9.96955C3.73253 10.4385 4.1127 10.8187 4.58168 10.8187C5.05067 10.8187 5.43083 10.4385 5.43083 9.96955V7.4221H13.5827V9.96955C13.5827 10.4385 13.9628 10.8187 14.4318 10.8187C14.9008 10.8187 15.281 10.4385 15.281 9.96955V7.4221H16.9793V20.6688Z" />
                        </svg>
                    </button>
                    <div class="hdr_cart_popup cart-dropdown-panel w-80 md:w-96 absolute right-0 top-full transform transition-all duration-300 group-hover:opacity-100 group-hover:visible group-hover:scale-100 z-50 bg-white dark:bg-title/95 p-5 md:p-[30px] border border-bdr-clr dark:border-bdr-clr-drk shadow-2xl rounded-xl">
                        <h4 class="font-medium leading-none mb-4 text-xl md:text-2xl">Cart List</h4>
                        <div class="cart-dropdown-scroll max-h-[300px] overflow-y-auto">
                            @forelse($cartItems as $id => $item)
                            <div class="hdr-cart-item">
                                <div class="cart-dropdown-item flex gap-[15px] relative pb-[15px] mb-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk group">
                                    <a href="{{ route('product-details', $item['slug']) }}" class="cart-dropdown-thumb block flex-none overflow-hidden rounded-lg">
                                        <img class="cart-dropdown-thumb-image w-full h-full object-cover" src="{{ image_url($item['image'] ?? null) }}" alt="{{ $item['name'] }}" onerror="this.onerror=null; this.src='{{ asset('assets/img/product/default.jpg') }}'">
                                    </a>
                                    <div class="cart-dropdown-details flex-1">
                                        <div class="cart-dropdown-meta flex items-center gap-2">
                                            <span class="text-[14px] md:text-[15px] leading-none block">{{ $item['category'] ?? 'Product' }}</span>
                                            <span class="w-[6px] h-[6px] rounded-full bg-primary"></span>
                                            <span class="text-[14px] md:text-[15px] leading-none block">{{ currency($item['price']) }}</span>
                                        </div>
                                        <h6 class="cart-dropdown-title text-base md:text-lg font-semibold mt-[10px]">
                                            <a href="{{ route('product-details', $item['slug']) }}" class="block">
                                                {{ $item['name'] }}
                                            </a>
                                        </h6>
                                        <p class="text-sm mt-2">Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                    <button onclick="removeFromCart('{{ $id }}')" class="cart-dropdown-remove absolute top-0 right-0 p-1 text-gray-400 hover:text-red-500" title="Remove from cart" aria-label="Remove {{ $item['name'] }} from cart">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z" fill="currentColor" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <p class="text-center py-4">Your cart is empty</p>
                            @endforelse
                        </div>
                        @if($cartCount > 0)
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-6">
                                <h6 class="text-lg md:text-xl font-semibold leading-none">Sub Total:</h6>
                                <h6 class="text-lg md:text-xl font-semibold leading-none">{{ currency($cartTotal) }}</h6>
                            </div>
                            <div class="flex items-center gap-4">
                                <a href="{{ url('/cart') }}" class="btn btn-outline btn-sm w-full" data-text="View Cart">
                                    <span>View Cart</span>
                                </a>
                                <a href="{{ url('/checkout') }}" class="btn btn-solid btn-sm w-full" data-text="Checkout">
                                    <span>Checkout</span>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

              
                <!-- Hamburger -->
                <button class="hamburger lg:hidden" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="stroke-current text-title dark:text-white w-8 h-8 transition-transform duration-300" viewBox="0 0 100 100">
                        <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" stroke-width="8" stroke-linecap="round" />
                        <path class="line line2" d="M 20,50 H 80" stroke-width="8" stroke-linecap="round" />
                        <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" stroke-width="8" stroke-linecap="round" />
                    </svg>
                </button>
                <!-- Dark Light -->
                <!-- <div class="w-[1px] bg-title/20 dark:bg-white/20 h-7 hidden sm:block"></div>
                <label class="switcher cursor-pointer order-first sm:order-last">
                    <input class="hidden" type="checkbox">
                    <img class="moon w-[22px] sm:w-7" src="{{ asset('assets/img/icon/simple-sun.svg') }}" alt="moon">
                    <img class="sun w-[22px] sm:w-7" src="{{ asset('assets/img/icon/simple-light.svg') }}" alt="sun">
                </label> -->
            </div>
        </div>

        <!-- Menu -->
        <div id="mobile-menu" class="site-header-menu main-menu absolute lg:static z-50 w-full lg:w-auto top-full left-0 bg-white/95 dark:bg-title/95 backdrop-blur-md lg:bg-transparent lg:dark:bg-transparent px-5 sm:px-[30px] py-[10px] sm:py-5 lg:px-0 lg:py-0 transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none lg:static">
            <ul class="site-header-menu-list flex flex-col lg:flex-row lg:items-center text-lg leading-none text-title dark:text-white lg:gap-[30px] space-y-4 lg:space-y-0">

                @foreach($headerMenus as $menu)
                    @php
                        $menuChildren = collect($menu->children ?? []);
                        $menuTitle = trim((string) $menu->title);
                        $menuHref = $menuUrl($menu->url ?? '#');
                        $isShopMenu = strcasecmp($menuTitle, 'Shop') === 0 || trim((string) ($menu->url ?? ''), '/') === 'shop';
                        $isActiveMenu = $isShopMenu
                            ? request()->routeIs('shop', 'shop.category')
                            : ($isMenuActive($menu->url ?? '#') || $menuChildren->contains(fn ($child) => $isMenuActive($child->url ?? '#')));
                    @endphp

                    @if($isShopMenu)
                    {{-- Shop categories dropdown --}}
                    <li class="relative parent-parent-menu-item shop-menu-item">
                        <a href="{{ route('shop') }}" class="home-link nav-shop-link inline-flex items-center px-3 rounded-full transition-all duration-200 hover:bg-primary/10 hover:text-primary{{ $isActiveMenu ? ' active' : '' }}" @if($isActiveMenu) aria-current="page" @endif>
                            {{ $menuTitle ?: 'Shop' }}
                        </a>
                        <ul class="sub-menu shop-dropdown-menu lg:absolute z-50 lg:top-full lg:left-0 lg:min-w-[260px] lg:bg-white lg:dark:bg-title">
                            @foreach($navCategories as $category)
                            <li class="shop-dropdown-group{{ $selectedCategorySlug === $category->slug || $category->children->contains('slug', $selectedCategorySlug) ? ' is-active' : '' }}">
                                <a href="{{ route('shop.category', ['category' => $category]) }}" class="sub-menu-item shop-dropdown-parent">
                                    <span class="shop-dropdown-label ">{{ $category->name }}</span>
                                    @if($category->children->isNotEmpty())
                                        <!-- <span class="shop-dropdown-arrow" aria-hidden="true"></span> -->
                                    @endif
                                </a>

                                @if($category->children->isNotEmpty())
                                <ul class="shop-dropdown-children">
                                    @foreach($category->children as $childCategory)
                                    <li class="{{ $selectedCategorySlug === $childCategory->slug ? 'is-active' : '' }}">
                                        <a href="{{ route('shop.category', ['category' => $childCategory]) }}" class="sub-menu-item shop-dropdown-child">
                                            {{ $childCategory->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @else
                    <li class="relative parent-parent-menu-item">
                        <a href="{{ $menuHref }}" class="sub-menu-item{{ $isActiveMenu ? ' active' : '' }}" @if($isActiveMenu) aria-current="page" @endif>
                            {{ $menuTitle }}
                        </a>
                        @if($menuChildren->isNotEmpty())
                        <ul class="sub-menu lg:absolute z-50 lg:top-full lg:left-0 lg:min-w-[220px] lg:bg-white lg:dark:bg-title lg:py-[15px] lg:pr-[30px]">
                            @foreach($menuChildren as $childMenu)
                            <li>
                                <a href="{{ $menuUrl($childMenu->url ?? '#') }}" class="sub-menu-item block whitespace-nowrap{{ $isMenuActive($childMenu->url ?? '#') ? ' active' : '' }}">
                                    {{ $childMenu->title }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endif
                @endforeach

            </ul>

        </div>
    </div>

    @if($navCategories->isNotEmpty())
    <div class="site-category-rail mobile-site-category-rail lg:hidden">
        <div class="container-fluid">
            <div class="site-category-rail__inner site-header-category-inner">
                <nav class="site-category-rail__list site-category-rail__list--mobile" aria-label="Mobile product categories">
                    <div class="site-category-item{{ blank($selectedCategorySlug) && request()->routeIs('shop') ? ' is-active' : '' }}">
                        <a href="{{ route('shop') }}" class="site-category-link site-category-link--mobile" @if(blank($selectedCategorySlug) && request()->routeIs('shop')) aria-current="page" @endif>
                            <span>All</span>
                        </a>
                    </div>

                    @foreach($navCategories as $category)
                        @php
                            $mobileActiveChild = $category->children->firstWhere('slug', $selectedCategorySlug);
                            $isMobileCategoryActive = $selectedCategorySlug === $category->slug || filled($mobileActiveChild);
                        @endphp
                        <div class="site-category-item{{ $isMobileCategoryActive ? ' is-active' : '' }}">
                            <a href="{{ route('shop.category', ['category' => $category]) }}" class="site-category-link site-category-link--mobile" @if($isMobileCategoryActive) aria-current="page" @endif>
                                <span>{{ $category->name }}</span>
                            </a>
                        </div>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>

    <div class="site-category-rail hidden lg:block">
        <div class="container-fluid">
            <div class="site-category-rail__inner site-header-category-inner">
                <nav class="site-category-rail__list" aria-label="Product categories">
                    @foreach($navCategories as $category)
                        @php
                            $activeChild = $category->children->firstWhere('slug', $selectedCategorySlug);
                            $isCategoryActive = $selectedCategorySlug === $category->slug || filled($activeChild);
                            $categoryImageUrl = $category->image ? asset('storage/' . $category->image) : null;
                            $featuredChild = $activeChild ?? $category->children->first();
                        @endphp

                        <div class="site-category-item{{ $isCategoryActive ? ' is-active' : '' }}">
                            <a href="{{ route('shop.category', ['category' => $category]) }}" class="site-category-link" @if($isCategoryActive) aria-current="page" @endif>
                                <span>{{ $category->name }}</span>
                                @if($category->children->isNotEmpty())
                                <span class="site-category-caret" aria-hidden="true"></span>
                                @endif
                            </a>

                            @if($category->children->isNotEmpty())
                            <div class="site-category-panel site-category-menu-panel">
                                <div class="site-category-menu-panel__level site-category-menu-panel__level--primary">
                                    @foreach($category->children as $childCategory)
                                    <div class="site-category-menu-panel__item{{ $selectedCategorySlug === $childCategory->slug ? ' is-active' : '' }}">
                                        <a href="{{ route('shop.category', ['category' => $childCategory]) }}" class="site-category-menu-panel__link">
                                            <span>{{ $childCategory->name }}</span>
                                            @if($childCategory->children->isNotEmpty())
                                                <span class="site-category-menu-panel__arrow" aria-hidden="true"></span>
                                            @endif
                                        </a>

                                        @if($childCategory->children->isNotEmpty())
                                        <div class="site-category-menu-panel__level site-category-menu-panel__level--secondary">
                                            @foreach($childCategory->children as $grandChildCategory)
                                            <a href="{{ route('shop.category', ['category' => $grandChildCategory]) }}" class="site-category-menu-panel__sub-link{{ $selectedCategorySlug === $grandChildCategory->slug ? ' is-active' : '' }}">
                                                {{ $grandChildCategory->name }}
                                            </a>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Header End -->

<script>
document.addEventListener('click', function (event) {
    const button = event.target.closest('.hamburger');
    const header = document.querySelector('.header-area');
    const menu = document.querySelector('.site-header-menu.main-menu');

    if (!button || !header || !menu) {
        return;
    }

    event.preventDefault();
    event.stopPropagation();
    event.stopImmediatePropagation();

    const isOpen = !header.classList.contains('mobile-menu-open');

    header.classList.toggle('mobile-menu-open', isOpen);
    menu.classList.toggle('mobile-open', isOpen);
    menu.classList.toggle('active', isOpen);
    button.classList.toggle('opened', isOpen);
    button.setAttribute('aria-expanded', String(isOpen));
    document.body.classList.toggle('menu_overlay', isOpen);
}, true);
</script>
