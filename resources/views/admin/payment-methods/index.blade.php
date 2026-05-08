@extends('admin.layouts.app')

@section('title', 'Payment Methods')

@section('content')
@php
    $gateways = [
        'razorpay' => [
            'title' => 'Razorpay',
            'icon' => 'fas fa-bolt',
            'note' => 'UPI, cards, netbanking and wallets for India.',
            'fields' => [
                ['name' => 'razorpay_key', 'label' => 'Key ID', 'placeholder' => 'rzp_test_xxxxxxxxxx', 'type' => 'text'],
                ['name' => 'razorpay_secret', 'label' => 'Secret Key', 'placeholder' => 'Razorpay secret key', 'type' => 'password'],
                ['name' => 'razorpay_webhook', 'label' => 'Webhook URL', 'placeholder' => 'https://yourdomain.com/webhooks/razorpay', 'type' => 'url'],
            ],
        ],
        'stripe' => [
            'title' => 'Stripe',
            'icon' => 'fab fa-stripe-s',
            'note' => 'Secure card payments and international checkout.',
            'fields' => [
                ['name' => 'stripe_key', 'label' => 'Publishable Key', 'placeholder' => 'pk_live_xxxxxxxxxx', 'type' => 'text'],
                ['name' => 'stripe_secret', 'label' => 'Secret Key', 'placeholder' => 'sk_live_xxxxxxxxxx', 'type' => 'password'],
                ['name' => 'stripe_webhook', 'label' => 'Webhook URL', 'placeholder' => 'https://yourdomain.com/webhooks/stripe', 'type' => 'url'],
            ],
        ],
        'phonepe' => [
            'title' => 'PhonePe',
            'icon' => 'fas fa-mobile-alt',
            'note' => 'PhonePe gateway for UPI and app payments.',
            'fields' => [
                ['name' => 'phonepe_merchant_id', 'label' => 'Merchant ID', 'placeholder' => 'PhonePe merchant ID', 'type' => 'text'],
                ['name' => 'phonepe_salt_key', 'label' => 'Salt Key', 'placeholder' => 'PhonePe salt key', 'type' => 'password'],
                ['name' => 'phonepe_salt_index', 'label' => 'Salt Index', 'placeholder' => '1', 'type' => 'text'],
                ['name' => 'phonepe_webhook', 'label' => 'Webhook URL', 'placeholder' => 'https://yourdomain.com/webhooks/phonepe', 'type' => 'url'],
            ],
        ],
    ];
@endphp

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Payment Methods</h1>
        <p class="text-gray-600 mt-1">Configure checkout payment options and gateway credentials.</p>
    </div>
    <a href="{{ route('admin.settings.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 flex items-center">
        <i class="fas fa-cog mr-2"></i>General Settings
    </a>
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('admin.payment-methods.update') }}" class="space-y-8">
    @csrf
    @method('PUT')

    <section class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
        <div class="flex items-center justify-between gap-6 flex-wrap">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Cash On Delivery</h2>
                <p class="text-gray-600 mt-1">Allow offline payment after delivery.</p>
            </div>
            <div class="flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-3 font-semibold text-gray-900">
                    <input type="checkbox" name="cod_enabled" value="1" {{ ($settings['cod_enabled'] ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-yellow-500">
                    Enabled
                </label>
                <label class="inline-flex items-center gap-3 font-semibold text-gray-900">
                    <input type="checkbox" name="cod_show_checkout" value="1" {{ ($settings['cod_show_checkout'] ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-yellow-500">
                    Show at checkout
                </label>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        @foreach($gateways as $gatewayKey => $gateway)
            @php
                $isEnabled = $settings[$gatewayKey . '_enabled'] ?? false;
                $isVisible = $settings[$gatewayKey . '_show_checkout'] ?? true;
            @endphp
            <section class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 bg-gray-900 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="{{ $gateway['icon'] }} text-xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $gateway['title'] }}</h2>
                        <p class="text-gray-600 text-sm mt-1">{{ $gateway['note'] }}</p>
                    </div>
                </div>

                <div class="space-y-5">
                    @foreach($gateway['fields'] as $field)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $field['label'] }}</label>
                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ old($field['name'], $settings[$field['name']] ?? '') }}" placeholder="{{ $field['placeholder'] }}"
                                   class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-200">
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 bg-gray-50 p-5 rounded-xl space-y-4">
                    <label class="flex items-center gap-3 font-semibold text-gray-900">
                        <input type="checkbox" name="{{ $gatewayKey }}_enabled" value="1" {{ $isEnabled ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-yellow-500">
                        Enable {{ $gateway['title'] }}
                    </label>
                    <label class="flex items-center gap-3 font-semibold text-gray-900">
                        <input type="checkbox" name="{{ $gatewayKey }}_show_checkout" value="1" {{ $isVisible ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-yellow-500">
                        Show at checkout
                    </label>
                </div>
            </section>
        @endforeach
    </div>

    <div class="flex flex-wrap gap-4">
        <button type="submit" class="bg-gray-900 text-white py-4 px-8 rounded-xl hover:bg-black font-semibold transition-all duration-200 shadow-lg flex items-center justify-center">
            <i class="fas fa-save mr-2"></i>Save Payment Configuration
        </button>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 text-gray-800 py-4 px-8 rounded-xl hover:bg-gray-200 font-semibold transition-all duration-200 flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</form>
@endsection
