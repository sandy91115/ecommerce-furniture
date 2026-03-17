<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Models\Product;

class WishlistController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $wishlistIds = session('wishlist', []);
        $wishlist = collect($wishlistIds)->map(function ($item, $id) {
            return Product::with('images')->find($id);
        })->filter()->values();

        return view('frontend.wishlist', compact('wishlist'));
    }

    public function add(Request $request, $productId)
    {
        $this->cartService->addToWishlist($productId);
        return response()->json(['success' => true, 'message' => 'Added to wishlist']);
    }

    public function remove(Request $request, $productId)
    {
        $this->cartService->removeFromWishlist($productId);
        return response()->json(['success' => true, 'message' => 'Removed from wishlist']);
    }
}

