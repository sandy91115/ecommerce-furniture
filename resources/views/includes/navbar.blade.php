<!-- Header Start -->
<div class="header-area default-header sticky top-0 z-50 bg-white dark:bg-title shadow-sm border-b border-bdr-clr dark:border-bdr-clr-drk transition-all duration-300">
    <div class="container-fluid">
        <div class="flex items-center justify-between gap-x-6 max-w-[1720px] mx-auto relative py-[10px] sm:py-4 lg:py-0">
            <!-- Logo -->
            <a class="cursor-pointer block" href="{{ url('/') }}" aria-label="CAROM STUDIOS">
                @php
                    $siteLogoPath = \App\Models\Setting::get('site_logo_path');
                    $logoUrl = $siteLogoPath ? asset('storage/' . $siteLogoPath) : asset('assets/img/footer-logo.svg');
                @endphp
                <img class="fill-current dark:text-white text-title w-[80px] sm:w-[150px] object-contain" src="{{ $logoUrl }}" alt="CAROM STUDIOS" onerror="this.outerHTML='<svg class=\"fill-current dark:text-white text-title w-[80px] sm:w-[150px]\" viewBox=\"0 0 201 39\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"> </svg>
            </a>

            <!-- Menu -->
            <div class="main-menu absolute lg:static z-50 w-full lg:w-auto top-full left-0 -translate-x-full lg:translate-x-0 bg-white/95 dark:bg-title/95 backdrop-blur-md lg:bg-transparent lg:dark:bg-transparent px-5 sm:px-[30px] py-[10px] sm:py-5 lg:px-0 lg:py-0 transition-transform duration-300 ease-in-out mobile-open:translate-x-0 shadow-2xl lg:shadow-none lg:static">
                <ul class="flex flex-col lg:flex-row lg:items-center text-lg leading-none text-title dark:text-white lg:gap-[30px] space-y-4 lg:space-y-0">

                      <li><a href="/" class="sub-menu-item">Home</a></li>
                    {{-- Shop Categories Dropdown --}}
                    <li class="relative parent-parent-menu-item">
                        <a href="/shop" class="home-link nav-shop-link inline-flex items-center px-3 py-2 rounded-full transition-all duration-200 hover:bg-primary/10 hover:text-primary{{ request()->is('shop') ? ' active' : '' }}" @if(request()->is('shop')) aria-current="page" @endif>
                            Shop
                          
                        </a>
                        <ul class="sub-menu lg:absolute z-50 lg:top-full lg:left-0 lg:min-w-[250px] lg:invisible lg:transition-all lg:bg-white lg:dark:bg-title lg:py-[15px] lg:pr-[30px]">
                            @foreach(\App\Models\Category::whereNull('parent_id')->take(12)->orderBy('name')->get() as $category)
                            <li><a href="/shop?category={{ $category->slug }}" class="sub-menu-item">{{ $category->name }}</a></li>
                            @endforeach
                            <li><a href="/shop" class="sub-menu-item">View All ({{ \App\Models\Product::count() }})</a></li>
                        </ul>
                    </li>

                     <li><a href="/blog" class="sub-menu-item">Quotation Request</a></li>
                    <li><a href="/blog" class="sub-menu-item">Blog</a></li>
                    <li><a href="/about" class="sub-menu-item">About Us</a></li>
                    <li><a href="/contact" class="sub-menu-item">Contact</a></li>

                </ul>

            </div>

            <!-- Header Right -->
            <div class="flex items-center gap-4 sm:gap-6">
                @auth
                <div class="relative group">
                    <a href="/my-profile" class="text-lg leading-none text-title dark:text-white transition-all duration-300 hover:text-primary hidden lg:block">
                        Hi, {{ auth()->user()->name }}
                        <i class="fas fa-chevron-down ml-1"></i>
                    </a>
                    <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-title shadow-lg rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                        <a href="/my-profile" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">My Account</a>
                        <a href="/order-history" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="text-lg leading-none text-title dark:text-white transition-all duration-300 hover:text-primary hidden lg:block">Login</a>
                @endauth

                <!-- Search -->
                <button class="hdr_search_btn" aria-label="search">
                    <svg class="fill-current text-title dark:text-white w-[18px] sm:w-[20px]" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.0703125 9.24982C0.0703125 4.18191 4.19363 0.0585938 9.26154 0.0585938C14.3297 0.0585938 18.4528 4.18191 18.4528 9.24982C18.4528 11.4791 17.655 13.5255 16.3301 15.1187L20.6993 19.4879C21.0307 19.819 21.0307 20.3564 20.6993 20.6876C20.5335 20.8533 20.3163 20.9361 20.0994 20.9361C19.8822 20.9361 19.6653 20.8533 19.4996 20.6876L15.1304 16.3183C13.5373 17.6433 11.4908 18.441 9.26154 18.441C4.19363 18.441 0.0703125 14.318 0.0703125 9.24982ZM1.76716 9.24986C1.76716 13.3822 5.12917 16.7442 9.26154 16.7442C13.3939 16.7442 16.7559 13.3822 16.7559 9.24982C16.7559 5.11745 13.3939 1.75544 9.26154 1.75544C5.12917 1.75544 1.76716 5.11749 1.76716 9.24986Z" />
                    </svg>
                </button>
                <!-- WishList -->
                <div class="relative group">
                    <button class="relative hdr_wishList_btn">
                        <span class="wishlist-count absolute w-[22px] h-[22px] bg-secondary -top-[10px] -right-[11px] rounded-full flex items-center justify-center text-xs leading-none text-white">{{ $wishlistCount ?? 0 }}</span>

                        <svg class="fill-current text-title dark:text-white w-[22px] sm:w-[25px]" viewBox="0 0 25 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.9005 0.591797C15.9541 0.591797 14.2479 1.45969 12.9662 3.10171C12.7953 3.3207 12.6429 3.53979 12.5079 3.75198C12.3728 3.53974 12.2205 3.3207 12.0496 3.10171C10.7679 1.45969 9.06162 0.591797 7.11524 0.591797C3.43837 0.591797 0.808594 3.67049 0.808594 7.36477C0.808594 11.589 4.27071 15.5701 12.0343 20.2733C12.1798 20.3614 12.3439 20.4055 12.5079 20.4055C12.6719 20.4055 12.8359 20.3615 12.9815 20.2733C20.7451 15.5702 24.2072 11.589 24.2072 7.36482C24.2072 3.67246 21.5795 0.591797 17.9005 0.591797ZM19.9642 12.6247C18.3479 14.4281 15.9055 16.327 12.5079 18.4205C9.11029 16.327 6.66784 14.4281 5.05155 12.6247C3.42654 10.8115 2.63661 9.09096 2.63661 7.36482C2.63661 4.70487 4.43419 2.41981 7.11524 2.41981C8.48059 2.41981 9.64476 3.01346 10.5754 4.1843C11.3196 5.12066 11.6332 6.08754 11.6354 6.09444C11.7544 6.47626 12.108 6.73634 12.5079 6.73634C12.9079 6.73634 13.2614 6.47631 13.3805 6.09444C13.3834 6.08521 13.6875 5.14849 14.4072 4.22644C15.3429 3.02762 16.5183 2.41976 17.9005 2.41976C20.5844 2.41976 22.3792 4.70702 22.3792 7.36477C22.3792 9.09092 21.5892 10.8114 19.9642 12.6247Z" />
                        </svg>
                    </button>
                    <div class="wishlist_popup w-80 md:w-96 absolute right-0 top-full opacity-0 invisible scale-95 transform transition-all duration-300 group-hover:opacity-100 group-hover:visible group-hover:scale-100 z-50 bg-white backdrop-blur-md py-5 md:py-[30px] pl-5 md:pl-[30px] pr-[10px] md:pr-[15px] border border-bdr-clr dark:border-bdr-clr-drk shadow-2xl rounded-xl">
                        <h4 class="font-medium leading-none dark:text-white mb-4 text-xl md:text-2xl">Wishlist ({{ $wishlistCount ?? 0 }})</h4>
                        @php $wishlist = session('wishlist', []); $wishlistItems = []; @endphp
                        <div class="max-h-[400px] overflow-y-auto">
                            @forelse($wishlist as $id => $item)
                            @php
                            $product = \App\Models\Product::with('images')->find($id);
                            if($product) {
                            $wishlistItems[] = $product;
                            }
                            @endphp
                            @empty
                            @endforelse

                            @forelse($wishlistItems as $product)
                            <div class="wishlist-item pb-[15px] mb-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk relative group/item">
                                <a href="{{ route('product-details', $product->slug) }}" class="flex items-center gap-[15px]">
                                    @php
                                    $wishlistImagePath = $product->images->first()?->path;
                                    $wishlistImageUrl = $wishlistImagePath ? asset('storage/' . $wishlistImagePath) : asset('assets/img/product/default.jpg');
                                    @endphp
                                    <img class="w-[70px] flex-shrink-0 object-cover rounded-lg" src="{{ $wishlistImageUrl }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('assets/img/product/default.jpg') }}'">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-[14px] text-gray-600 dark:text-gray-400">Product</span>
                                            <span class="w-[6px] h-[6px] rounded-full bg-primary"></span>
                                            <span class="text-[14px] font-semibold">${{ number_format($product->sale_price ?: $product->price, 2) }}</span>
                                        </div>
                                        <h6 class="text-base font-semibold truncate">{{ $product->name }}</h6>
                                    </div>
                                </a>
                                <button onclick="removeFromWishlist('{{ $product->id }}')" class="absolute top-0 right-0 w-8 h-8 flex items-center justify-center bg-title/10 dark:bg-white/10 hover:bg-primary text-title hover:text-white rounded-lg transition-all duration-200" title="Remove from wishlist">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p>Your wishlist is empty</p>
                            </div>
                            @endforelse
                        </div>
                        @if(count($wishlistItems) > 0)
                        <div class="mt-6 pt-4 border-t border-bdr-clr dark:border-bdr-clr-drk">
                            <a href="{{ route('frontend.wishlist') }}" class="w-full block text-center py-3 font-semibold bg-primary text-white rounded-lg">View All Wishlist Items</a>
                        </div>
                        @endif
                    </div>
                </div>

                <script>
                    function removeFromWishlist(id) {
                        if (!confirm('Remove this item from wishlist?')) return;

                        fetch('/wishlist/remove/' + id, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                }
                            })
                            .catch(err => console.error('Error:', err));
                    }
                </script>
                <!-- Cart -->
                <div class="relative group">
                    <button class="relative hdr_cart_btn">
                        <span class="cart-count absolute w-[22px] h-[22px] bg-secondary -top-[10px] -right-[11px] rounded-full flex items-center justify-center text-xs leading-none text-white">{{ $cartCount }}</span>
                        <svg class="fill-current text-title dark:text-white w-[18px] sm:w-[19px]" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.8284 5.7238H15.2408C14.9035 2.85886 12.4608 0.628906 9.50675 0.628906C6.55269 0.628906 4.11002 2.85886 3.7727 5.7238H1.18509C0.716102 5.7238 0.335938 6.10397 0.335938 6.57295V21.518C0.335938 21.987 0.716102 22.3671 1.18509 22.3671H17.8284C18.2974 22.3671 18.6776 21.987 18.6776 21.518V6.57295C18.6776 6.10397 18.2974 5.7238 17.8284 5.7238ZM9.50675 2.3272C11.5228 2.3272 13.2014 3.79857 13.5257 5.7238H5.48777C5.81214 3.79857 7.4907 2.3272 9.50675 2.3272ZM16.9793 20.6688H2.03424V7.4221H3.73253V9.96955C3.73253 10.4385 4.1127 10.8187 4.58168 10.8187C5.05067 10.8187 5.43083 10.4385 5.43083 9.96955V7.4221H13.5827V9.96955C13.5827 10.4385 13.9628 10.8187 14.4318 10.8187C14.9008 10.8187 15.281 10.4385 15.281 9.96955V7.4221H16.9793V20.6688Z" />
                        </svg>
                    </button>
                    <div class="hdr_cart_popup w-80 md:w-96 absolute right-0 top-full opacity-0 invisible scale-95 transform transition-all duration-300 group-hover:opacity-100 group-hover:visible group-hover:scale-100 z-50 bg-white/95 dark:bg-title/95 backdrop-blur-md p-5 md:p-[30px] border border-bdr-clr dark:border-bdr-clr-drk shadow-2xl rounded-xl">
                        <h4 class="font-medium leading-none mb-4 text-xl md:text-2xl">Cart List</h4>
                        <div class="max-h-[300px] overflow-y-auto">
                            @forelse($cartItems as $id => $item)
                            <div class="hdr-cart-item">
                                <div class="flex gap-[15px] relative pb-[15px] mb-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk group">
                                    <a href="{{ route('product-details', $item['slug']) }}" class="block w-20 h-20 flex-none">
                                        <img class="w-full h-full object-cover" src="{{ !empty($item['image']) ? asset('storage/' . $item['image']) : asset('assets/img/product/default.jpg') }}" alt="{{ $item['name'] }}" onerror="this.src='{{ asset('assets/img/product/default.jpg') }}'">
                                    </a>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[14px] md:text-[15px] leading-none block">{{ $item['category'] ?? 'Product' }}</span>
                                            <span class="w-[6px] h-[6px] rounded-full bg-primary"></span>
                                            <span class="text-[14px] md:text-[15px] leading-none block">${{ number_format($item['price'], 2) }}</span>
                                        </div>
                                        <h6 class="text-base md:text-lg font-semibold !leading-none mt-[10px]">
                                            <a href="{{ route('product-details', $item['slug']) }}">
                                                {{ $item['name'] }}
                                            </a>
                                        </h6>
                                        <p class="text-sm mt-2">Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                    <button onclick="removeFromCart('{{ $id }}')" class="absolute top-0 right-0 p-1 text-gray-400 hover:text-red-500">
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
                                <h6 class="text-lg md:text-xl font-semibold leading-none">${{ number_format($cartTotal, 2) }}</h6>
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

                <script>
                    if (typeof removeFromCart !== 'function') {
                        function removeFromCart(id) {
                            if (!confirm('Are you sure you want to remove this item?')) return;

                            fetch("{{ route('cart.remove') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        id: id
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        location.reload();
                                    }
                                });
                        }
                    }
                </script>
                <!-- Hamburger -->
                <button class="hamburger lg:hidden" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="stroke-current text-title dark:text-white w-8 h-8 transition-transform duration-300" viewBox="0 0 100 100">
                        <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" stroke-width="8" stroke-linecap="round" />
                        <path class="line line2" d="M 20,50 H 80" stroke-width="8" stroke-linecap="round" />
                        <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" stroke-width="8" stroke-linecap="round" />
                    </svg>
                </button>
                <!-- Dark Light -->
                <div class="w-[1px] bg-title/20 dark:bg-white/20 h-7 hidden sm:block"></div>
                <label class="switcher cursor-pointer order-first sm:order-last">
                    <input class="hidden" type="checkbox">
                    <img class="moon w-[22px] sm:w-7" src="{{ asset('assets/img/icon/simple-sun.svg') }}" alt="moon">
                    <img class="sun w-[22px] sm:w-7" src="{{ asset('assets/img/icon/simple-light.svg') }}" alt="sun">
                </label>
            </div>
        </div>
    </div>
</div>
<!-- Search -->
<div class="search_popup fixed top-0 left-0 bg-red dark:bg-[#39434D] bg-opacity-90 dark:bg-opacity-80 backdrop-blur-[3px] dark:backdrop-blur-[7.5px] w-full h-screen z-[999] px-[15px] md:px-[30px] py-12 md:py-[70px] overflow-y-auto transform scale-90 opacity-0 invisible transition-all duration-300 flex items-center justify-center">
    <div class="container">
        <div class="relative max-w-4xl mx-auto hdr-search-wrapper">
            <button class="hdr_search_close w-[36px] h-[36px] absolute bottom-full md:top-0 right-0 flex items-center justify-center bg-title dark:bg-white text-white dark:text-title">
                <svg class="fill-current" width="15" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.742 12.0717C11.6006 12.2131 11.445 12.2838 11.2753 12.2838C11.1056 12.2838 10.9501 12.2131 10.8086 12.0717L6.16295 7.42598L1.55968 12.0292C1.41826 12.1707 1.2627 12.2414 1.09299 12.2414C0.923289 12.2414 0.767726 12.1707 0.626304 12.0292L0.32932 11.7323C0.187898 11.5908 0.117187 11.4353 0.117188 11.2656C0.117187 11.0959 0.187898 10.9403 0.329319 10.7989L4.93258 6.19561L0.414172 1.6772C0.272751 1.53578 0.20204 1.38021 0.20204 1.21051C0.20204 1.0408 0.272751 0.885239 0.414172 0.743817L0.73237 0.42562C0.873792 0.284198 1.02935 0.213487 1.19906 0.213487C1.36877 0.213488 1.52433 0.284198 1.66575 0.42562L6.18416 4.94403L10.8086 0.319553C10.9501 0.178132 11.1056 0.107421 11.2753 0.107422C11.445 0.107422 11.6006 0.178133 11.742 0.319554L12.039 0.616539C12.1804 0.75796 12.2511 0.913524 12.2511 1.08323C12.2511 1.25293 12.1804 1.4085 12.039 1.54992L7.41453 6.1744L12.0602 10.8201C12.2016 10.9615 12.2724 11.1171 12.2724 11.2868C12.2724 11.4565 12.2016 11.612 12.0602 11.7535L11.742 12.0717Z" />
                </svg>
            </button>

            <div class="bg-white dark:bg-title py-8 sm:py-10 md:py-[60px] px-5 sm:px-8">
                <!-- Input -->
                <div class="relative">
                    <form action="/search" method="GET" class="w-full">
                        <input name="q" class="outline-none border-b border-bdr-clr dark:border-bdr-clr-drk pb-4 md:pb-[22px] text-title w-full pr-7 md:pr-10 leading-none font-lg placeholder:text-title bg-transparent dark:bg-transparent dark:text-white dark:placeholder:text-white" type="text" placeholder="Search products..." value="{{ request('q') }}">
                    </form>

                    <button class="absolute right-0 top-0">
                        <svg class="fill-current text-title dark:text-white w-5 md:w-[30px]" viewBox="0 0 30 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M29.5439 28.2361L22.1484 20.5625C24.0499 18.3074 25.0917 15.4701 25.0917 12.5162C25.0917 5.61489 19.4635 0 12.5459 0C5.62818 0 0 5.61489 0 12.5162C0 19.4176 5.62818 25.0325 12.5459 25.0325C15.1429 25.0325 17.6177 24.251 19.7335 22.7676L27.1852 30.4994C27.4967 30.8221 27.9156 31 28.3646 31C28.7895 31 29.1926 30.8384 29.4986 30.5445C30.1488 29.9203 30.1695 28.8853 29.5439 28.2361ZM12.5459 3.26511C17.6591 3.26511 21.8189 7.41506 21.8189 12.5162C21.8189 17.6174 17.6591 21.7674 12.5459 21.7674C7.43261 21.7674 3.27283 17.6174 3.27283 12.5162C3.27283 7.41506 7.43261 3.26511 12.5459 3.26511Z" />
                        </svg>
                    </button>
                </div>
                <!-- Tags -->
                <div class="mt-10 md:mt-12">
                    <h4 class="font-medium leading-none text-2xl">Popular Tags</h4>
                    <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Chair"><span>Chair</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Art & Paint"><span>Art & Paint</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Mirror"><span>Mirror</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Table"><span>Table</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Lamp"><span>Lamp</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<script>
    const currentPath = window.location.pathname.replace(/\/$/, '');

    const subMenuItems = document.querySelectorAll('.sub-menu-item');
    subMenuItems.forEach((item) => {
        const itemPath = new URL(item.href).pathname.replace(/\/$/, '');

        if (itemPath === currentPath) {
            item.classList.add('active');

            // Highlight all parent menus recursively
            let parentMenu = item.closest('.parent-menu-item');
            while (parentMenu && !parentMenu.classList.contains('processed')) {
                const parentLink = parentMenu.querySelector('a');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
                parentMenu.classList.add('processed');
                parentMenu = parentMenu.closest('.parent-parent-menu-item');
            }

            // Highlight the top-level parent menu
            const topLevelMenu = item.closest('.parent-parent-menu-item');
            if (topLevelMenu) {
                const topLevelLink = topLevelMenu.querySelector('.home-link');
                if (topLevelLink) {
                    topLevelLink.classList.add('active');
                }
            }
        }
    });
</script>
