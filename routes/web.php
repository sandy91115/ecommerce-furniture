<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogV2Controller;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioV2Controller;
use App\Http\Controllers\ContactController;

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
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login.action');
Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register.action');
Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});
Route::get('/my-profile', [HomeController::class, 'myProfile']);
Route::get('/my-account', [HomeController::class, 'myAccount']);
Route::get('/edit-account', [HomeController::class, 'editAccount']);
Route::get('/order-history', [HomeController::class, 'orderHistory']);
Route::get('/wishlist', [HomeController::class, 'wishlist']);

// Checkout Mock Pages
Route::get('/shipping-method', [HomeController::class, 'shippingMethod']);
Route::get('/payment-method', [HomeController::class, 'paymentMethod']);
Route::get('/payment-success/{order}', [HomeController::class, 'paymentSuccess'])->name('payment-success');
Route::get('/payment-failure', [HomeController::class, 'paymentFailure']);
Route::get('/payment-confirmation', [HomeController::class, 'paymentConfirmation']);
Route::get('/invoice', [HomeController::class, 'invoice']);
Route::get('/thank-you', [HomeController::class, 'thankYou']);
Route::get('/cart', [HomeController::class, 'cart']);

// Shop
Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop');
Route::get('/shop-v2', [HomeController::class, 'shopV2']);
Route::get('/shop-v3', [HomeController::class, 'shopV3']);
Route::get('/shop-v4', [HomeController::class, 'shopV4']);
Route::get('/product-category', [HomeController::class, 'productCategory']);
Route::get('/product-details', [HomeController::class, 'productDetails']);
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product-details');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog-v2', [HomeController::class, 'blogV2'])->name('blog.v2');
Route::get('/blog-details-v1/{title?}', [BlogController::class, 'show'])->name('blog-details-v1');
Route::get('/blog-details-v2/{title?}', [BlogV2Controller::class, 'show'])->name('blog-details-v2');
Route::get('/blog-details-v3/{title?}', [HomeController::class, 'blogDetailsV3'])->name('blog-details-v3');
Route::get('/blog-details/{slug?}', [BlogController::class, 'show'])->name('blog-details');
Route::get('/blog-tag', [HomeController::class, 'blogTag']);

// Contact
Route::get('/contact', [HomeController::class, 'contact']);
Route::get('/contactus', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contactus', [ContactController::class, 'send'])->name('contact.send');

// Vendor Store Page
Route::get('/store/{vendor:slug}', [App\Http\Controllers\StoreController::class, 'show'])->name('store.show');

// Frontend Cart/Wishlist Routes
Route::middleware('auth')->prefix('account')->name('frontend.account.')->group(function () {
    Route::get('/', function () { return view('frontend.account'); })->name('index');
    Route::get('/orders', function () { return view('frontend.orders'); })->name('orders');
});

Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('frontend.wishlist');

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

// Admin Routes
Route::get('/admin/login', function () {
    return redirect('/login');
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::get('products/pending', [App\Http\Controllers\Admin\ProductController::class, 'pending'])->name('products.pending');
    Route::post('products/{product}/approve', [App\Http\Controllers\Admin\ProductController::class, 'approve'])->name('products.approve');
    Route::post('products/{product}/reject', [App\Http\Controllers\Admin\ProductController::class, 'reject'])->name('products.reject');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('attributes', App\Http\Controllers\Admin\AttributeController::class);
    Route::get('attributes/{attribute}/values', [App\Http\Controllers\Admin\AttributeController::class, 'values'])->name('attributes.values');
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);
Route::resource('vendors', App\Http\Controllers\Admin\VendorController::class);
Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
    Route::resource('cms', App\Http\Controllers\Admin\CMSController::class);
    Route::resource('menus', App\Http\Controllers\Admin\MenuController::class);
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

// Vendor Routes
Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Vendor\VendorProductController::class);
    Route::resource('orders', App\Http\Controllers\Vendor\VendorOrderController::class);
    Route::get('/profile', [App\Http\Controllers\Vendor\VendorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Vendor\VendorProfileController::class, 'update'])->name('profile.update');
});
