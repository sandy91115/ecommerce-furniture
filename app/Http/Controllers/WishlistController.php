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
        $wishlistIds = array_keys(session('wishlist', []));

        $wishlist = Product::query()
            ->with('images')
            ->whereIn('id', $wishlistIds)
            ->get();

        // For frontend.wishlist view
        return view('frontend.wishlist', compact('wishlist'));
    }

    public function accountWishlist()
    {
        $wishlistIds = array_keys(session('wishlist', []));

        $wishlistProducts = Product::query()
            ->with('images')
            ->whereIn('id', $wishlistIds)
            ->get();

        $wishlistIds = session('wishlist', []);

        return view('wishlist', compact('wishlistProducts', 'wishlistIds'));
    }

    public function add(Request $request, $productId)
    {
        $this->cartService->addToWishlist($productId);

        $count = $this->cartService->wishlistCount();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Added to wishlist', 'count' => $count]);
        }

        return back()->with('success', 'Added to wishlist');
    }

    public function remove(Request $request, $productId)
    {
        $this->cartService->removeFromWishlist($productId);

        $count = $this->cartService->wishlistCount();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Removed from wishlist', 'count' => $count]);
        }

        return back()->with('success', 'Removed from wishlist');
    }

    public function toggle(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = (int) $data['product_id'];
        $wishlist = session('wishlist', []);

        $added = !array_key_exists($productId, $wishlist);

        if ($added) {
            $this->cartService->addToWishlist($productId);
        } else {
            $this->cartService->removeFromWishlist($productId);
        }

        return response()->json([
            'success' => true,
            'added' => $added,
            'count' => $this->cartService->wishlistCount(),
            'message' => $added ? 'Added to wishlist' : 'Removed from wishlist',
        ]);
    }
}

