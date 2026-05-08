<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
<<<<<<< HEAD
use App\Models\PaymentTransaction;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
=======
use Illuminate\Support\Facades\Auth;
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

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
<<<<<<< HEAD
        $cartItems = $this->cartService->validateForCheckout();
=======
        $cartItems = $this->cartService->get();
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        if (empty($cartItems)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        $summary = $this->cartService->summary($cartItems);
<<<<<<< HEAD
        $paymentMethods = $this->availablePaymentMethods();
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return view('checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $summary['subtotal'],
            'tax' => $summary['tax'],
            'total' => $summary['total'],
            'taxLabel' => $summary['tax_label'],
<<<<<<< HEAD
            'paymentMethods' => $paymentMethods,
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        ]);
    }

    public function process(Request $request)
    {
<<<<<<< HEAD
        $paymentMethods = $this->availablePaymentMethods();
        $availableMethodKeys = array_keys($paymentMethods);

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
            'terms' => 'accepted',
<<<<<<< HEAD
            'payment_method' => ['required', Rule::in($availableMethodKeys)],
        ]);

        $cartItems = $this->cartService->validateForCheckout();
=======
        ]);

        $cartItems = $this->cartService->get();
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        if (empty($cartItems)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        $address = "{$request->address}, {$request->city}, {$request->state} {$request->zipcode}, {$request->country}";
<<<<<<< HEAD
        $selectedPayment = $paymentMethods[$request->payment_method];
        $isGatewayPayment = $selectedPayment['type'] === 'gateway';
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        
        $orderData = [
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $this->cartService->grandTotal(),
            'status' => 'pending',
<<<<<<< HEAD
            'payment_status' => $isGatewayPayment ? 'pending' : 'pending',
            'payment_method' => $isGatewayPayment ? 'online' : 'cod',
            'payment_gateway' => $isGatewayPayment ? $request->payment_method : null,
=======
            'payment_status' => 'pending',
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'shipping_address' => $address,
            'items' => array_values($cartItems),
        ];

<<<<<<< HEAD
        $order = DB::transaction(function () use ($orderData, $isGatewayPayment, $selectedPayment) {
            $order = $this->orderService->create($orderData);

            PaymentTransaction::create([
                'order_id' => $order->id,
                'gateway' => $order->payment_gateway ?: 'cod',
                'transaction_id' => $isGatewayPayment ? 'INIT-' . strtoupper(uniqid()) : null,
                'amount' => $order->total_amount,
                'status' => $isGatewayPayment ? 'initiated' : 'cod_pending',
                'payload' => [
                    'label' => $selectedPayment['label'],
                    'created_from' => 'checkout',
                ],
            ]);

            return $order;
        });

        if (! $isGatewayPayment) {
            $this->cartService->clear();
        }

        return response()->json([
            'success' => true,
            'message' => $isGatewayPayment
                ? 'Order #' . $order->order_number . ' created. Continue to secure payment.'
                : 'Order #' . $order->order_number . ' placed successfully!',
            'redirect' => $isGatewayPayment ? route('payment-confirmation') . '?order=' . $order->id : route('payment-success', $order->id),
            'payment_required' => $isGatewayPayment,
            'gateway' => $order->payment_gateway,
        ]);
    }

    private function availablePaymentMethods(): array
    {
        $methods = [];

        if (Setting::bool(Setting::COD_ENABLED, true) && Setting::bool(Setting::COD_SHOW_CHECKOUT, true)) {
            $methods['cod'] = [
                'label' => 'Cash On Delivery',
                'type' => 'offline',
                'description' => 'Pay when your order is delivered.',
            ];
        }

        $gatewaySettings = [
            'razorpay' => [
                'label' => 'Razorpay',
                'enabled' => Setting::RAZORPAY_ENABLED,
                'show' => Setting::RAZORPAY_SHOW_CHECKOUT,
                'key' => Setting::RAZORPAY_KEY,
                'secret' => Setting::RAZORPAY_SECRET,
                'description' => 'UPI, cards, netbanking and wallets.',
            ],
            'stripe' => [
                'label' => 'Stripe',
                'enabled' => Setting::STRIPE_ENABLED,
                'show' => Setting::STRIPE_SHOW_CHECKOUT,
                'key' => Setting::STRIPE_KEY,
                'secret' => Setting::STRIPE_SECRET,
                'description' => 'International cards and secure online payment.',
            ],
            'phonepe' => [
                'label' => 'PhonePe',
                'enabled' => Setting::PHONEPE_ENABLED,
                'show' => Setting::PHONEPE_SHOW_CHECKOUT,
                'key' => Setting::PHONEPE_MERCHANT_ID,
                'secret' => Setting::PHONEPE_SALT_KEY,
                'description' => 'UPI and PhonePe payment gateway.',
            ],
        ];

        foreach ($gatewaySettings as $key => $gateway) {
            if (
                Setting::bool($gateway['enabled'])
                && Setting::bool($gateway['show'], true)
                && filled(Setting::get($gateway['key']))
                && filled(Setting::get($gateway['secret']))
            ) {
                $methods[$key] = [
                    'label' => $gateway['label'],
                    'type' => 'gateway',
                    'description' => $gateway['description'],
                ];
            }
        }

        return $methods;
    }
=======
        $order = $this->orderService->create($orderData);

        $this->cartService->clear();

        return response()->json([
            'success' => true,
            'message' => 'Order #' . $order->order_number . ' placed successfully!',
            'redirect' => route('payment-success', $order->id)
        ]);
    }
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
}
