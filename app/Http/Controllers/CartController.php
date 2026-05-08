<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required_without:qty|integer|min:1',
            'qty' => 'required_without:quantity|integer|min:1',
            'attribute' => 'nullable|array',
            'attribute.*' => 'required|exists:attribute_values,id'
        ]);

        $quantity = (int) ($data['quantity'] ?? $data['qty']);
        $attributes = $data['attribute'] ?? [];
        $redirectTo = $request->input('redirect_to');

        $cart = $this->cartService->add($data['product_id'], $quantity, null, $attributes);

        Session::put('cart_count', $this->cartService->count());

        if ($request->expectsJson() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $this->cartService->count(),
                'cart_total' => $this->cartService->total(),
            ]);
        }

        if (is_string($redirectTo) && Str::startsWith($redirectTo, '/') && ! Str::startsWith($redirectTo, '//')) {
            return redirect($redirectTo);
        }

        return redirect()->route('frontend.cart')
            ->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->update($request->id, $request->quantity);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => collect($exception->errors())->flatten()->first() ?: 'Unable to update cart.',
            ], 422);
        }

        return response()->json($this->cartPayload('Cart updated successfully.'));
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|string'
        ]);

        $this->cartService->remove($request->id);

        return response()->json($this->cartPayload('Product removed from cart.'));
    }

    private function cartPayload(string $message): array
    {
        $cart = $this->cartService->get();
        $summary = $this->cartService->summary($cart);

        return [
            'success' => true,
            'message' => $message,
            'cart_count' => $this->cartService->count(),
            'subtotal' => currency($summary['subtotal']),
            'tax' => currency($summary['tax']),
            'total' => currency($summary['total']),
            'is_empty' => empty($cart),
        ];
    }
}
