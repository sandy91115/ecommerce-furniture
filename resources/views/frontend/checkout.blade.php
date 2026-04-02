@extends('layouts.main')

@section('title', 'Checkout')

@section('content')
@php
    $cartItems = session('cart', []);
    $cartSubtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cartItems));
    $cartTax = $cartSubtotal * 0.1;
    $cartTotal = $cartSubtotal + $cartTax;
@endphp
<div class="s-py-100">
    <div class="container">
        <div class="max-w-4xl mx-auto" data-aos="fade-up">
            <div class="flex items-center gap-3 mb-10">
                <a href="/" class="inline-flex items-center gap-2 text-title dark:text-white text-base hover:text-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Home
                </a>
                <span class="text-primary font-semibold">/ Checkout</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold mb-12">Checkout</h1>

            @if (count($cartItems) > 0)
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Billing & Shipping Info -->
                <div class="space-y-6">
                    <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-dark-secondary shadow-sm">
                        <h2 class="text-2xl font-bold mb-6 dark:text-white">Billing Details</h2>
                        <form id="billing-form">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">First Name *</label>
                                    <input type="text" name="first_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Last Name *</label>
                                    <input type="text" name="last_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Company Name</label>
                                    <input type="text" name="company" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Email Address *</label>
                                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Phone *</label>
                                    <input type="tel" name="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Country / Region *</label>
                                    <select name="country" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Select country</option>
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="GB">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Address *</label>
                                    <input type="text" name="address" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2 dark:text-white">City *</label>
                                    <input type="text" name="city" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2 dark:text-white">State *</label>
                                        <input type="text" name="state" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2 dark:text-white">Post Code / ZIP *</label>
                                        <input type="text" name="zipcode" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="terms" required class="mr-2">
                                        <span class="text-sm dark:text-white">I agree to the <a href="#" class="text-primary hover:underline">terms and conditions</a> *</span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-dark-secondary shadow-sm">
                        <h2 class="text-2xl font-bold mb-6 dark:text-white">Additional Information</h2>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Order Notes (Optional)</label>
                            <textarea name="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="space-y-6">
                    <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-dark-secondary shadow-sm sticky top-8">
                        <h2 class="text-2xl font-bold mb-6 dark:text-white">Your Order</h2>
                        <div class="space-y-4 mb-8">
                            @foreach ($cartItems as $id => $item)
                            <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-b-0">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('assets/img/product/default.jpg') }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded-lg" onerror="this.onerror=null; this.src=\'{{ asset(\'assets/img/product/default.jpg\') }}\'">
                                <div class="flex-1">
                                    <h4 class="font-semibold dark:text-white">{{ $item['name'] }}</h4>
                                    @if(!empty($item['attributes']))
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @foreach($item['attributes'] as $attr)
                                        <span class="text-[10px] bg-gray-50 dark:bg-gray-800 px-1.5 py-0.5 rounded text-gray-500">
                                            {{ $attr['attribute_name'] }}: {{ $attr['value'] }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <span class="font-bold dark:text-white">{{ currency($item['price'] * $item['quantity']) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-3 mb-8">
                            <div class="flex justify-between">
                                <span class="dark:text-white">Subtotal:</span>
                                <span class="font-semibold">{{ currency($cartSubtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="dark:text-white">Shipping:</span>
                                <span>Free</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="dark:text-white">Tax (10%):</span>
                                <span class="font-semibold">{{ currency($cartTax) }}</span>
                            </div>
                        </div>

                        <div class="border-t pt-6 border-gray-200">
                            <div class="flex justify-between items-center text-xl font-bold mb-6">
                                <span class="dark:text-white">Total:</span>
                                <span class="text-primary">{{ currency($cartTotal) }}</span>
                            </div>
                            <button id="place-order" class="btn btn-solid w-full" data-text="Place Order">
                                <span>Place Order</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-20">
                <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h18l-2 12H5L3 3zM12 14a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                <h3 class="text-2xl font-semibold mb-4 dark:text-white">No items in cart</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-8">Your cart is empty. Add some products to checkout.</p>
                <a href="{{ route('shop') }}" class="btn btn-solid" data-text="Shop Now">
                    <span>Shop Now</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.getElementById('place-order').addEventListener('click', function() {
        const form = document.getElementById('billing-form');
        const formData = new FormData(form);
        const notesField = document.querySelector('textarea[name="notes"]');
        if (notesField) {
            formData.append('notes', notesField.value);
        }

        // Disable button to prevent double submission
        const btn = document.getElementById('place-order');
        btn.disabled = true;
        btn.innerHTML = 'Processing...';

        fetch('/checkout', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw err;
                });
            }
            return response.json();
        }).then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Something went wrong');
                btn.disabled = false;
                btn.innerHTML = 'Place Order';
            }
        }).catch(error => {
            console.error('Error:', error);
            let message = 'An error occurred. Please try again.';
            if (error.errors) {
                message = Object.values(error.errors).flat().join('\n');
            } else if (error.message) {
                message = error.message;
            }
            alert(message);
            btn.disabled = false;
            btn.innerHTML = 'Place Order';
        });

    });
</script>

@endsection
