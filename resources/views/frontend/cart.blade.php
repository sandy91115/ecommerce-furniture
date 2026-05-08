@extends('layouts.main')

@section('title', 'Shopping Cart')

<<<<<<< HEAD
@push('head')
<style>
    .cart-page {
        background: linear-gradient(180deg, #fbfaf7 0%, #ffffff 42%, #f7f3ea 100%);
    }

    .cart-shell {
        max-width: 1320px;
        margin: 0 auto;
    }

    .cart-kicker {
        letter-spacing: 0.22em;
        color: #c8a018;
    }

    .cart-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 390px;
        gap: 28px;
        align-items: start;
    }

    .cart-card,
    .cart-summary-card {
        border: 1px solid rgba(23, 36, 48, 0.12);
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 18px 46px rgba(31, 28, 20, 0.08);
    }

    .cart-card {
        display: grid;
        grid-template-columns: 132px minmax(0, 1fr) auto;
        gap: 22px;
        padding: 22px;
        align-items: center;
    }

    .cart-media {
        aspect-ratio: 1;
        background: #f3efe5;
        overflow: hidden;
    }

    .cart-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-qty-control {
        display: inline-grid;
        grid-template-columns: 38px 48px 38px;
        height: 40px;
        border: 1px solid rgba(23, 36, 48, 0.18);
        background: #f8f5ef;
        overflow: hidden;
    }

    .cart-qty-control button,
    .cart-qty-control input {
        width: 100%;
        height: 100%;
        border: 0;
        background: transparent;
        text-align: center;
        color: #172430;
        font-weight: 700;
        outline: 0;
    }

    .cart-qty-control button {
        display: grid;
        place-items: center;
        cursor: pointer;
        transition: background 160ms ease, color 160ms ease;
    }

    .cart-qty-control button:hover:not(:disabled) {
        background: #f0cf3d;
    }

    .cart-qty-control button:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }

    .cart-qty-control input::-webkit-outer-spin-button,
    .cart-qty-control input::-webkit-inner-spin-button {
        appearance: none;
        margin: 0;
    }

    .cart-qty-control input {
        -moz-appearance: textfield;
    }

    .cart-summary-card {
        position: sticky;
        top: 110px;
        padding: 28px;
    }

    .cart-action {
        height: 54px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(23, 36, 48, 0.18);
        font-weight: 700;
        transition: transform 160ms ease, border-color 160ms ease, background 160ms ease;
    }

    .cart-action:hover {
        transform: translateY(-1px);
        border-color: #f0cf3d;
    }

    .cart-action-primary {
        background: #f0cf3d;
        border-color: #f0cf3d;
        color: #111827;
    }

    @media (max-width: 1100px) {
        .cart-layout {
            grid-template-columns: 1fr;
        }

        .cart-summary-card {
            position: static;
        }
    }

    @media (max-width: 720px) {
        .cart-card {
            grid-template-columns: 96px minmax(0, 1fr);
        }

        .cart-card__side {
            grid-column: 1 / -1;
            align-items: flex-start;
        }
    }
</style>
@endpush

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
@section('content')
@php
    $cartItems = $cart ?? session('cart', []);
    $cartSummary = cart_summary($cartItems);
    $cartSubtotal = $cartSummary['subtotal'];
    $cartTax = $cartSummary['tax'];
    $cartTotal = $cartSummary['total'];
    $cartTaxLabel = $cartSummary['tax_label'];
@endphp
<<<<<<< HEAD

<section class="cart-page s-py-100">
    <div class="container-fluid">
        <div class="cart-shell">
            <div class="mb-10">
                <ul class="flex items-center gap-3 text-sm md:text-base text-title dark:text-white">
                    <li><a href="{{ url('/') }}" class="hover:text-primary">Home</a></li>
                    <li>/</li>
                    <li class="text-primary">Cart</li>
                </ul>
                <p class="cart-kicker uppercase text-xs font-semibold mt-8">Your Selection</p>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mt-3 text-title dark:text-white">Shopping Cart</h1>
            </div>

            @if (count($cartItems) > 0)
                <div class="cart-layout">
                    <div class="grid gap-5" id="cart-items">
                        @foreach ($cartItems as $id => $item)
                            <article class="cart-card" data-cart-item data-cart-id="{{ $id }}" data-price="{{ (float) $item['price'] }}">
                                <a href="{{ route('product-details', $item['slug']) }}" class="cart-media">
                                    <img src="{{ image_url($item['image']) }}" alt="{{ $item['name'] }}">
                                </a>

                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-[0.18em] text-primary font-semibold mb-2">{{ $item['category'] ?? 'Coffee Tables' }}</p>
                                    <h2 class="text-xl md:text-2xl font-bold leading-snug text-title dark:text-white">
                                        <a href="{{ route('product-details', $item['slug']) }}" class="hover:text-primary">{{ $item['name'] }}</a>
                                    </h2>

                                    @if(!empty($item['attributes']))
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            @foreach($item['attributes'] as $attr)
                                                <span class="text-xs bg-[#F4EFE4] px-3 py-1 text-title">
                                                    {{ $attr['attribute_name'] }}: {{ $attr['value'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-4 mt-5">
                                        <span class="text-sm font-semibold text-paragraph dark:text-white-light">Qty</span>
                                        <div class="cart-qty-control" data-cart-qty>
                                            <button type="button" data-cart-decrement aria-label="Decrease quantity">-</button>
                                            <input type="number" min="1" value="{{ $item['quantity'] }}" data-cart-quantity aria-label="Quantity for {{ $item['name'] }}">
                                            <button type="button" data-cart-increment aria-label="Increase quantity">+</button>
                                        </div>
                                        <button type="button" class="text-sm font-semibold text-red-500 hover:text-red-700" data-cart-remove>Remove</button>
                                    </div>
                                </div>

                                <div class="cart-card__side flex flex-col items-end gap-2">
                                    <span class="text-sm text-paragraph dark:text-white-light">{{ currency($item['price']) }} each</span>
                                    <strong class="text-2xl text-primary" data-cart-row-total>{{ currency($item['price'] * $item['quantity']) }}</strong>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="cart-summary-card">
                        <h2 class="text-2xl font-bold text-title dark:text-white">Cart Totals</h2>
                        <div class="grid gap-4 mt-7">
                            <div class="flex justify-between gap-4 text-lg">
                                <span class="text-paragraph dark:text-white-light">Subtotal</span>
                                <strong id="cart-subtotal">{{ currency($cartSubtotal) }}</strong>
                            </div>
                            <div class="flex justify-between gap-4 text-lg">
                                <span class="text-paragraph dark:text-white-light">{{ $cartTaxLabel }}</span>
                                <strong id="cart-tax">{{ currency($cartTax) }}</strong>
                            </div>
                            <div class="border-t border-bdr-clr pt-5 mt-2 flex justify-between gap-4 text-2xl font-bold text-primary">
                                <span>Total</span>
                                <span id="cart-total">{{ currency($cartTotal) }}</span>
                            </div>
                        </div>

                        <div class="grid gap-3 mt-8">
                            <a href="{{ route('shop') }}" class="cart-action">Continue Shopping</a>
                            <a href="{{ route('frontend.checkout') }}" class="cart-action cart-action-primary">Proceed to Checkout</a>
                        </div>
                    </aside>
                </div>
            @else
                <div class="cart-card flex flex-col items-center text-center py-20">
                    <h2 class="text-3xl font-bold text-title dark:text-white">Your cart is empty</h2>
                    <p class="max-w-md mt-4 text-paragraph dark:text-white-light">Add a handcrafted piece to your cart and it will appear here for checkout.</p>
                    <a href="{{ route('shop') }}" class="cart-action cart-action-primary px-10 mt-8">Start Shopping</a>
                </div>
            @endif
        </div>
    </div>
</section>

@if (count($cartItems) > 0)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const formatter = new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function setBusy(item, isBusy) {
        item.querySelectorAll('button, input').forEach((control) => {
            control.disabled = isBusy;
        });
        item.classList.toggle('opacity-70', isBusy);
    }

    function updateSummary(data) {
        document.getElementById('cart-subtotal').textContent = data.subtotal;
        document.getElementById('cart-tax').textContent = data.tax;
        document.getElementById('cart-total').textContent = data.total;

        document.querySelectorAll('.cart-count').forEach((counter) => {
            counter.textContent = data.cart_count;
        });
    }

    function refreshIfEmpty(data) {
        if (data.is_empty || document.querySelectorAll('[data-cart-item]').length === 0) {
            window.location.reload();
        }
    }

    async function postCart(url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Cart update failed.');
        }

        return data;
    }

    async function updateItemQuantity(item, nextQuantity) {
        const quantity = Math.max(1, parseInt(nextQuantity, 10) || 1);
        const input = item.querySelector('[data-cart-quantity]');
        const rowTotal = item.querySelector('[data-cart-row-total]');
        const price = parseFloat(item.dataset.price || '0');

        setBusy(item, true);

        try {
            const data = await postCart('{{ route('cart.update') }}', {
                id: item.dataset.cartId,
                quantity
            });

            input.value = quantity;
            rowTotal.textContent = formatter.format(price * quantity);
            updateSummary(data);
        } catch (error) {
            alert(error.message);
            window.location.reload();
        } finally {
            setBusy(item, false);
        }
    }

    document.querySelectorAll('[data-cart-item]').forEach((item) => {
        const input = item.querySelector('[data-cart-quantity]');

        item.querySelector('[data-cart-decrement]').addEventListener('click', function () {
            updateItemQuantity(item, (parseInt(input.value, 10) || 1) - 1);
        });

        item.querySelector('[data-cart-increment]').addEventListener('click', function () {
            updateItemQuantity(item, (parseInt(input.value, 10) || 1) + 1);
        });

        input.addEventListener('change', function () {
            updateItemQuantity(item, input.value);
        });

        item.querySelector('[data-cart-remove]').addEventListener('click', async function () {
            setBusy(item, true);

            try {
                const data = await postCart('{{ route('cart.remove') }}', {
                    id: item.dataset.cartId
                });

                item.remove();
                updateSummary(data);
                refreshIfEmpty(data);
            } catch (error) {
                alert(error.message);
                window.location.reload();
            }
        });
    });
});
</script>
@endif

@include('includes.footer')
=======
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

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
@endsection
