<?php

namespace App\Providers;

use App\Services\WishlistService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Contracts\OrderRepositoryInterface;
use App\Repositories\OrderRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(\App\Contracts\CouponRepositoryInterface::class, \App\Repositories\CouponRepository::class);
    }

    public function boot(): void
    {
        require_once app_path('helpers/tax_helpers.php');
        
        Gate::before(function ($user, string $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        view()->composer('*', function ($view) {
            $cartService = app(\App\Services\CartService::class);
            $wishlistService = app(WishlistService::class);
            $cartItems = $cartService->get();
            $cartTotal = $cartService->total();
            
            $view->with('cartItems', $cartItems);
            $view->with('cartTotal', $cartTotal);
            $view->with('cartCount', $cartService->count());
            $view->with('wishlistCount', $wishlistService->count());
            $view->with('wishlistItems', $wishlistService->getItems());
            $view->with('wishlistIds', $wishlistService->ids());
            
            $view->with('menus', [
                'header_main' => \App\Models\Menu::getMenus('header_main'),
                'footer_sitemap' => \App\Models\Menu::getMenus('footer_sitemap'),
                'footer_others' => \App\Models\Menu::getMenus('footer_others'),
                'footer_shop' => \App\Models\Menu::getMenus('footer_shop'),
                'footer_service' => \App\Models\Menu::getMenus('footer_service'),
                'admin_sidebar' => \App\Models\Menu::getMenus('admin_sidebar'),
            ]);

            // Dynamic testimonials
            $testimonials = \App\Models\Review::where('status', 'approved')
<<<<<<< HEAD
                ->whereRaw("TRIM(COALESCE(comment, '')) <> ''")
                ->whereHas('product', fn ($query) => $query->where('status', 'active'))
                ->with(['user:id,name', 'product:id,name,slug,status'])
                ->inRandomOrder()
                ->limit(5)
=======
                ->with('user:id,name')
                ->inRandomOrder()
                ->limit(3)
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                ->get();
            $view->with('testimonials', $testimonials);
        });
    }
}
