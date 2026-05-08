@extends('layouts.main')

@section('title', 'Shopping Cart')

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

@section('content')
@php
    $cartItems = $cart ?? session('cart', []);
    $cartSummary = cart_summary($cartItems);
    $cartSubtotal = $cartSummary['subtotal'];
    $cartTax = $cartSummary['tax'];
    $cartTotal = $cartSummary['total'];
    $cartTaxLabel = $cartSummary['tax_label'];
@endphp

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
@endsection
