<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WishlistService;
use App\Models\Product;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function accountWishlist()
    {
        $wishlistItems = $this->wishlistService->getItems();
        return view('frontend.wishlist', compact('wishlistItems'));
    }

    public function add(Request $request, Product $product)
    {
        $this->wishlistService->add($product->id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'added' => true,
                'count' => $this->wishlistService->count(),
                'message' => 'Added to wishlist',
            ]);
        }

        return back()->with('success', 'Added to wishlist');
    }

    public function remove(Request $request, Product $product)
    {
        $this->wishlistService->remove($product->id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'added' => false,
                'count' => $this->wishlistService->count(),
                'message' => 'Removed from wishlist',
            ]);
        }

        return back()->with('success', 'Removed from wishlist');
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $added = $this->wishlistService->toggle($request->integer('product_id'));

        return response()->json([
            'success' => true,
            'added' => $added,
            'count' => $this->wishlistService->count(),
            'message' => $added ? 'Added to wishlist' : 'Removed from wishlist',
        ]);
    }
}
