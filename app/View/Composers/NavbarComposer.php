<?php

namespace App\View\Composers;

use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\View\View;

class NavbarComposer
{
    protected $cartService;
    protected $wishlistService;

    public function __construct(CartService $cartService, WishlistService $wishlistService)
    {
        $this->cartService = $cartService;
        $this->wishlistService = $wishlistService;
    }

    public function compose(View $view)
    {
        $cartItems = $this->cartService->get();
        $view->with([
            'cartItems' => $cartItems,
            'cartCount' => $this->cartService->count(),
            'cartTotal' => $this->cartService->total(),
            'wishlistCount' => $this->wishlistService->count(),
            'wishlistItems' => $this->wishlistService->getItems(),
        ]);
    }
}
