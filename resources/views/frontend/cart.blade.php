@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('content')
@php
    $cartItems = session('cart', []);
    $cartSubtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cartItems));
    $cartTax = $cartSubtotal * 0.1;
    $cartTotal = $cartSubtotal + $cartTax;
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
                        <img src="{{ isset($item['image']) && $item['image'] ? asset('storage/' . $item['image']) : asset('assets/img/product/default.jpg') }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/img/product/default.jpg') }}';">
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
                                <input type="number" value="{{ $item['quantity'] }}" min="1" class="w-16 h-10 border border-gray-300 dark:border-gray-600 rounded-lg text-center dark:bg-dark-secondary dark:text-white" onchange="updateCart('{{ $id }}', this.value)">
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
                        <span class="dark:text-white">Tax (10%):</span>
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
