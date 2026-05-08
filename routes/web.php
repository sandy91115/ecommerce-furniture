<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\BlogV2Controller;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioV2Controller;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicStorageController;

Route::get('/storage/{path}', [PublicStorageController::class, 'show'])
    ->where('path', '.*')
    ->name('public.storage');

Route::get('/robots.txt', [App\Http\Controllers\SeoPublicController::class, 'robots'])->name('seo.robots');
Route::get('/sitemap.xml', [App\Http\Controllers\SeoPublicController::class, 'sitemapIndex'])->name('seo.sitemap');
Route::get('/sitemap-products.xml', [App\Http\Controllers\SeoPublicController::class, 'products'])->name('seo.sitemap.products');
Route::get('/sitemap-categories.xml', [App\Http\Controllers\SeoPublicController::class, 'categories'])->name('seo.sitemap.categories');
Route::get('/sitemap-pages.xml', [App\Http\Controllers\SeoPublicController::class, 'pages'])->name('seo.sitemap.pages');
Route::get('/sitemap-images.xml', [App\Http\Controllers\SeoPublicController::class, 'images'])->name('seo.sitemap.images');

// Frontend Routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/index-v2', [HomeController::class, 'indexV2']);
Route::get('/index-v3', [HomeController::class, 'indexV3']);
Route::get('/index-v4', [HomeController::class, 'indexV4']);
Route::get('/index-v5', [HomeController::class, 'indexV5']);
Route::get('/index-v6', [HomeController::class, 'indexV6']);

// Frontend Pages
Route::get('/about', [HomeController::class, 'about']);
Route::get('/pricing', [HomeController::class, 'pricing']);
Route::get('/team', [HomeController::class, 'team']);
Route::get('/our-clients', [HomeController::class, 'ourClients']);
Route::get('/faq', [HomeController::class, 'faq']);
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions']);
Route::get('/coming-soon', [HomeController::class, 'comingSoon'])->name('coming-soon');
Route::get('/return-policy', [HomeController::class, 'returnPolicy']);
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy']);

// Portfolio
Route::get('/portfolio-v1', [HomeController::class, 'portfolioV1']);
Route::get('/portfolio-v2', [HomeController::class, 'portfolioV2']);
Route::get('/portfolio-v3', [HomeController::class, 'portfolioV3']);
Route::get('/portfolio-details-v1', [HomeController::class, 'portfolioDetailsV1']);
Route::get('/portfolio-details-v1/{title}', [PortfolioController::class, 'show'])->name('portfolio-details-v1');
Route::get('/portfolio-details-v2', [HomeController::class, 'portfolioDetailsV2']);
Route::get('/portfolio-details-v2/{title}', [PortfolioV2Controller::class, 'show'])->name('portfolio-details-v2');

// Universal Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [App\Http\Controllers\Auth\AuthController::class, 'showRegisterForm'])->name('register');
});
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->middleware(['guest', 'throttle:5,1'])->name('login.action');
Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register'])->middleware(['guest', 'throttle:5,1'])->name('register.action');
Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-profile', [HomeController::class, 'myProfile'])->name('my-profile');
    Route::get('/my-account', [HomeController::class, 'myAccount']);
    Route::get('/edit-account', [HomeController::class, 'editAccount'])->name('edit-account');
    Route::post('/edit-account', [HomeController::class, 'updateAccount'])->name('update-account');
});
Route::get('/order-history', [HomeController::class, 'orderHistory'])->middleware('auth')->name('order-history');
Route::get('/quotation', [HomeController::class, 'quotationHistory'])->middleware('auth')->name('quotation-history');

// Checkout pages
Route::get('/shipping-method', [HomeController::class, 'shippingMethod']);
Route::get('/payment-method', [HomeController::class, 'paymentMethod']);
Route::get('/payment-success/{order}', [HomeController::class, 'paymentSuccess'])->middleware('auth')->name('payment-success');
Route::get('/payment-failure', [HomeController::class, 'paymentFailure']);
Route::get('/payment-confirmation', [HomeController::class, 'paymentConfirmation']);
Route::get('/invoice', [HomeController::class, 'invoice']);
Route::get('/thank-you', [HomeController::class, 'thankYou']);

// Shop
Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop');
Route::get('/category/{category:slug}', [App\Http\Controllers\ShopController::class, 'index'])->name('shop.category');
Route::get('/shop-v2', [HomeController::class, 'shopV2']);
Route::get('/shop-v3', [HomeController::class, 'shopV3']);
Route::get('/shop-v4', [HomeController::class, 'shopV4']);
Route::get('/product-category', [HomeController::class, 'productCategory']);
Route::get('/product-details', [HomeController::class, 'productDetails']);
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product-details');
Route::get('/quotation-request', [ProductController::class, 'quotationProducts'])->name('quotation-products.index');

Route::get('/quotation/{product:slug}', [App\Http\Controllers\Admin\QuotationController::class, 'showForm'])->name('quotation.form');
Route::post('/quotation/{product:slug}', [App\Http\Controllers\Admin\QuotationController::class, 'storeQuotation'])->name('quotation.store');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{blog:slug}/comments', [BlogCommentController::class, 'store'])->name('blog.comments.store');
Route::get('/blog-v2', [HomeController::class, 'blogV2']);
Route::get('/blog-details-v1/{title?}', [BlogController::class, 'show'])->name('blog-details-v1');
Route::get('/blog-details-v2/{title?}', [BlogV2Controller::class, 'show'])->name('blog-details-v2');
Route::get('/blog-details-v3/{title?}', [HomeController::class, 'blogDetailsV3'])->name('blog-details-v3');
Route::get('/blog-details/{slug?}', [BlogController::class, 'show'])->name('blog-details');
Route::get('/blog-tag', [HomeController::class, 'blogTag']);

// Contact
Route::get('/contact', [HomeController::class, 'contact']);
Route::get('/contactus', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contactus', [ContactController::class, 'send'])->name('contact.send');

// Store (single-vendor)
Route::get('/store', [App\Http\Controllers\StoreController::class, 'index'])->name('store.index');
Route::get('/store/{any}', function () {
    return redirect()->route('store.index');
})->where('any', '.*');

Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'accountWishlist'])->name('frontend.wishlist');

Route::get('/cart', function () {
    $cartService = new \App\Services\CartService();
    $cart = $cartService->get();
    return view('frontend.cart', compact('cart'));
})->name('frontend.cart');

use App\Http\Controllers\CheckoutController;

Route::get('/checkout', [CheckoutController::class, 'index'])->name('frontend.checkout')->middleware('auth');

Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process')->middleware('auth');

Route::get('/search', [ProductController::class, 'search'])->name('frontend.search');

// Cart & Wishlist Routes
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::post('/wishlist/add/{product}', [App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
Route::delete('/wishlist/remove/{product}', [App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');

// Review Routes
Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::get('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'frontendReviews'])->name('products.reviews');


// Admin Routes
$adminPath = trim(env('ADMIN_PATH', 'panel'), '/');
$adminPath = $adminPath !== '' ? $adminPath : 'panel';

Route::middleware('guest')->group(function () use ($adminPath) {
    Route::get('/' . $adminPath . '/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/' . $adminPath . '/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.action');
});

Route::get('/admin/login', function () {
    return redirect()->route('admin.login');
});

Route::get('/admin', function () {
    if (! auth()->check()) {
        return redirect()->route('admin.login');
    }

    return redirect()->route('admin.dashboard');
});

Route::get('/admin/{adminMenuAlias}', function (string $adminMenuAlias) use ($adminPath) {
    return redirect('/' . $adminPath . '/' . trim($adminMenuAlias, '/'));
})->where('adminMenuAlias', '.*');

Route::get('/' . $adminPath, function () {
    if (! auth()->check()) {
        return redirect()->route('admin.login');
    }

    return redirect()->route('admin.dashboard');
});

Route::prefix($adminPath)->name('admin.')->middleware(['auth', 'permission:admin.access'])->group(function () {
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/seo', [App\Http\Controllers\Admin\SeoController::class, 'index'])->name('seo.index');
    Route::get('/seo/health', [App\Http\Controllers\Admin\SeoController::class, 'health'])->name('seo.health');
    Route::post('/seo/health/refresh', [App\Http\Controllers\Admin\SeoController::class, 'refreshHealth'])->name('seo.health.refresh');
    Route::get('/seo/health/status', [App\Http\Controllers\Admin\SeoController::class, 'healthStatus'])->name('seo.health.status');
    Route::get('/seo/settings', [App\Http\Controllers\Admin\SeoController::class, 'settings'])->name('seo.settings');
    Route::put('/seo/settings', [App\Http\Controllers\Admin\SeoController::class, 'updateSettings'])->name('seo.settings.update');
    Route::get('/seo/pages', [App\Http\Controllers\Admin\SeoController::class, 'pages'])->name('seo.pages');
    Route::put('/seo/pages/{pageKey}', [App\Http\Controllers\Admin\SeoController::class, 'updatePage'])->name('seo.pages.update');
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::get('products/export', [App\Http\Controllers\Admin\ProductController::class, 'export'])->name('products.export');
    Route::get('products/import-template', [App\Http\Controllers\Admin\ProductController::class, 'importTemplate'])->name('products.import-template');
    Route::post('products/import', [App\Http\Controllers\Admin\ProductController::class, 'import'])->name('products.import');
    Route::get('products/quotation', [App\Http\Controllers\Admin\ProductController::class, 'quotationProducts'])->name('products.quotation');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('attributes', App\Http\Controllers\Admin\AttributeController::class);
    Route::get('attributes/{attribute}/values', [App\Http\Controllers\Admin\AttributeController::class, 'values'])->name('attributes.values');
    Route::get('attributes/{attribute}/values/create', [App\Http\Controllers\Admin\AttributeController::class, 'valuesCreate'])->name('attributes.values.create');
    Route::post('attributes/{attribute}/values', [App\Http\Controllers\Admin\AttributeController::class, 'valuesStore'])->name('attributes.values.store');
    Route::get('attributes/{attribute}/values/{value}/edit', [App\Http\Controllers\Admin\AttributeController::class, 'valuesEdit'])->name('attributes.values.edit');
    Route::put('attributes/{attribute}/values/{value}', [App\Http\Controllers\Admin\AttributeController::class, 'valuesUpdate'])->name('attributes.values.update');
    Route::delete('attributes/{attribute}/values/{value}', [App\Http\Controllers\Admin\AttributeController::class, 'valuesDestroy'])->name('attributes.values.destroy');
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);
    Route::post('customers/{customer}/verify', [App\Http\Controllers\Admin\CustomerController::class, 'verify'])->name('customers.verify');
    Route::post('customers/{customer}/reject', [App\Http\Controllers\Admin\CustomerController::class, 'reject'])->name('customers.reject');
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
    Route::resource('banners', App\Http\Controllers\Admin\HomeBannerController::class)->except(['show']);
    Route::resource('cms', App\Http\Controllers\Admin\CMSController::class);
    Route::resource('blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class);
    Route::resource('menus', App\Http\Controllers\Admin\MenuController::class);
    Route::resource('partners', App\Http\Controllers\Admin\PartnerController::class)->except(['show']);
    Route::resource('contacts', App\Http\Controllers\Admin\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::resource('quotations', App\Http\Controllers\Admin\QuotationController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::put('payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class);
    Route::patch('reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('reviews/{review}/reject', [App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::resource('staff', App\Http\Controllers\Admin\StaffController::class);
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::get('trash', [App\Http\Controllers\Admin\TrashController::class, 'index'])->name('trash.index');
    Route::post('trash/{type}/{id}/restore', [App\Http\Controllers\Admin\TrashController::class, 'restore'])->name('trash.restore');
    Route::delete('trash/{type}/{id}', [App\Http\Controllers\Admin\TrashController::class, 'forceDelete'])->name('trash.force-delete');

    Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    Route::get('{adminMenuAlias}', App\Http\Controllers\Admin\MenuAliasController::class)
        ->where('adminMenuAlias', '.*')
        ->name('menu-alias');
});

Route::get('/{page:slug}', [App\Http\Controllers\CmsPageController::class, 'show'])
    ->where('page', '^(?!storage$|admin$|panel$|api$).+')
    ->name('cms.page');
