@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('content')
@php
    $cartItems = $cart ?? session('cart', []);
    $cartSummary = cart_summary($cartItems);
    $cartSubtotal = $cartSummary['subtotal'];
    $cartTax = $cartSummary['tax'];
    $cartTotal = $cartSummary['total'];
    $cartTaxLabel = $cartSummary['tax_label'];
@endphp
<div class="s-py-100">
    <div class="container">
        <div class="max-w-2xl mx-auto" data-aos="fade-up">
            <div class="flex items-center gap-3 mb-10">
                <a href="/" class="inline-flex items-center gap-2 text-title dark:text-white text-base hover:text-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Home
                </a>
                <span class="text-primary font-semibold">/ Cart</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold mb-12">Shopping Cart</h1>

            @if (count($cartItems) > 0)
            <div class="space-y-6">
                @foreach ($cartItems as $id => $item)
                <div class="flex gap-6 p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-dark-secondary shadow-sm">
                    <a href="{{ route('product-details', $item['slug']) }}" class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="{{ image_url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                    </a>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl font-semibold dark:text-white mb-2">
                            <a href="{{ route('product-details', $item['slug']) }}" class="hover:text-primary">{{ $item['name'] }}</a>
                        </h3>
                        @if(!empty($item['attributes']))
                        <div class="flex flex-wrap gap-3 mb-4">
                            @foreach($item['attributes'] as $attr)
                            <span class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-gray-600 dark:text-gray-400">
                                <strong>{{ $attr['attribute_name'] }}:</strong> {{ $attr['value'] }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                        <p class="text-gray-600 dark:text-gray-300 mb-4 truncate">{{ $item['name'] }}</p>
                        <div class="flex items-center gap-4 flex-wrap">
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium dark:text-white">Qty:</label>
                                <div class="inc-dec flex items-center gap-2">
                                    <button type="button" onclick="updateQuantity('{{ $id }}', -1)" class="dec w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                                        <svg class="fill-current text-title dark:text-white" width="14" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.4361 0.203613H12.0736L7.81774 0.203615H13.8729V1.80309H7.81774L3.50809 1.80309H1.87053L6.18017 1.80309H0.125V0.203615H6.18017L10.4361 0.203613Z" />
                                        </svg>
                                    </button>
                                    <input id="cartQty{{ $id }}" class="w-10 h-auto outline-none bg-transparent text-base md:text-lg leading-none text-title dark:text-white text-center" type="text" value="{{ $item['quantity'] }}" onchange="updateCart('{{ $id }}', this.value)" min="1">
                                    <button type="button" onclick="updateQuantity('{{ $id }}', 1)" class="inc w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                                        <svg class="fill-current text-title dark:text-white" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.18017 0.110352H7.81774V6.16553H13.8729V7.76501H7.81774V13.8963H6.18017V7.76501H0.125V6.16553H6.18017V0.110352Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <span class="text-2xl font-bold text-primary">{{ currency($item['price'] * $item['quantity']) }}</span>
                            <button onclick="removeFromCart('{{ $id }}')" class="text-red-500 hover:text-red-700 font-medium">Remove</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-dark-secondary shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold dark:text-white">Cart Totals</h2>
                </div>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-lg">
                        <span class="dark:text-white">Subtotal:</span>
                        <span class="font-semibold">{{ currency($cartSubtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="dark:text-white">{{ $cartTaxLabel }}:</span>
                        <span class="font-semibold">{{ currency($cartTax) }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-2xl font-bold text-primary">
                        <span>Total:</span>
                        <span>{{ currency($cartTotal) }}</span>
                    </div>
                </div>
                <div class="flex gap-4">
                    <a href="/" class="btn btn-outline flex-1" data-text="Continue Shopping">
                        <span>Continue Shopping</span>
                    </a>
                    <a href="/checkout" class="btn btn-solid flex-1" data-text="Proceed to Checkout">
                        <span>Proceed to Checkout</span>
                    </a>

                </div>
            </div>
            @else
            <div class="text-center py-20">
                <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h18l-2 12H5L3 3zM12 14a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                <h3 class="text-2xl font-semibold mb-4 dark:text-white">Your cart is empty</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-8">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('shop') }}" class="btn btn-solid" data-text="Start Shopping">
                    <span>Start Shopping</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateQuantity(id, change) {
    const input = document.getElementById('cartQty' + id);
    if (!input) return;
    
    let quantity = parseInt(input.value || 1) + change;
    if (quantity < 1) quantity = 1;
    
    input.value = quantity;
    updateCart(id, quantity);
}

function updateCart(id, quantity) {
    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id: id,
            quantity: parseInt(quantity)
        })
    }).then(response => response.json()).then(data => {
        location.reload();
    });
}

    function removeFromCart(id) {
        if (confirm('Remove this item?')) {
            fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: id
                })
            }).then(response => response.json()).then(data => {
                location.reload();
            });
        }
    }
</script>

@endsection
