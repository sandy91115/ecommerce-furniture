<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'attribute' => 'nullable|array',
            'attribute.*' => 'required|exists:attribute_values,id'
        ]);

        $cart = $this->cartService->add($request->product_id, $request->quantity, null, $request->attribute);

        Session::put('cart_count', $this->cartService->count());

        return redirect()->route('frontend.cart')
            ->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $this->cartService->update($request->id, $request->quantity);

        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|string'
        ]);

        $this->cartService->remove($request->id);

        return response()->json(['success' => true]);
    }
}
?>
