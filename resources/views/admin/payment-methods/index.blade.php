@extends('admin.layouts.app')

@section('title', 'Payment Methods')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Payment Methods</h1>
        <p class="text-gray-600 mt-1">Configure payment gateways for checkout</p>
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Razorpay Gateway -->
    <div class="lg:col-span-2 bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
        <div class="flex items-center mb-8">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-4 flex-shrink-0">
                <i class="fas fa-credit-card text-2xl text-white"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Razorpay</h2>
                <p class="text-gray-600">India's leading payment gateway. Secure & reliable.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.payment-methods.update', 1) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">API Key <span class="text-red-500">*</span></label>
                    <input type="text" name="razorpay_key" value="{{ $settings['razorpay_key'] ?? '' }}" placeholder="rzp_test_xxxxxxxxxx" 
                           class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">
                    <p class="text-xs text-gray-500 mt-1">Get from Razorpay Dashboard > API Keys</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Secret Key <span class="text-red-500">*</span></label>
                    <input type="password" name="razorpay_secret" value="{{ $settings['razorpay_secret'] ?? '' }}" placeholder="your_secret_key_here" 
                           class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Webhook Endpoint (Optional)</label>
                <input type="url" name="razorpay_webhook" value="{{ $settings['razorpay_webhook'] ?? '' }}" 
                       placeholder="https://yourdomain.com/api/webhook/razorpay" 
                       class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                <p class="text-xs text-gray-500 mt-2">For automatic order confirmation & inventory sync</p>
            </div>

            <div class="bg-gray-50 p-6 rounded-xl">
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="razorpay_enabled" id="razorpay_enabled" {{ ($settings['razorpay_enabled'] ?? false) ? 'checked' : '' }} 
                               class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="razorpay_enabled" class="ml-3 block text-sm font-semibold text-gray-900">Enable Razorpay</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="razorpay_show_checkout" id="razorpay_show_checkout" {{ ($settings['razorpay_show_checkout'] ?? true) ? 'checked' : '' }} 
                               class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="razorpay_show_checkout" class="ml-3 block text-sm font-semibold text-gray-900">Show at Checkout</label>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-4 px-8 rounded-xl hover:from-purple-700 hover:to-indigo-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Save Configuration
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex-1 bg-gray-100 text-gray-800 py-4 px-8 rounded-xl hover:bg-gray-200 font-semibold transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </form>

        <div class="mt-8 p-6  border border-blue-200 rounded-xl">
            <h3 class="text-1xl font-bold text-gray-900">Status</h3>
            <div class="flex items-center gap-4">
                <div class="flex items-center {{ ($settings['razorpay_enabled'] ?? false) ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100' }} px-4 py-2 rounded-lg">
                    <div class="w-2 h-2 bg-current rounded-full mr-2"></div>
                    <span>{{ ($settings['razorpay_enabled'] ?? false) ? '✅ Active' : '⚠️ Disabled' }}</span>
                </div>
                <span class="text-sm text-gray-600">Live mode when API keys configured</span>
            </div>
        </div>
    </div>
</div>
@endsection

