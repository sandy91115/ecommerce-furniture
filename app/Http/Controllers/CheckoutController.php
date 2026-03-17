<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cartItems = $this->cartService->get();
        if (empty($cartItems)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        $subtotal = $this->cartService->total();
        $tax = $subtotal * 0.10; // 10% tax
        $total = $subtotal + $tax;

        return view('checkout', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'country' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'required|string',
            'terms' => 'required',
        ]);

        $cartItems = $this->cartService->get();

        if (empty($cartItems)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        $address = "{$request->address}, {$request->city}, {$request->state} {$request->zipcode}, {$request->country}";
        
        $orderData = [
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $this->cartService->total() * 1.1,
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_address' => $address,
            'items' => array_values($cartItems),
        ];

        $order = $this->orderService->create($orderData);

        $this->cartService->clear();

        return response()->json([
            'success' => true,
            'message' => 'Order #' . $order->order_number . ' placed successfully!',
            'redirect' => route('payment-success', $order->id)
        ]);
    }
}
?>


