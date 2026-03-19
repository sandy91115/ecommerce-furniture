@extends('layouts.main')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="bg-white shadow-xl rounded-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
            <img src="{{ $product->images->first()->getUrl() ?? asset('assets/img/product/default.jpg') }}" alt="{{ $product->name }}" class="w-48 h-48 object-cover mx-auto rounded-xl shadow-lg mb-4">
            <p class="text-xl font-semibold text-gray-900">${{ number_format($product->price, 2) }}</p>
            @if($product->extra_title)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <h3 class="font-bold text-blue-900">{{ $product->extra_title }}</h3>
                    {!! $product->extra_description !!}
                </div>
            @endif
        </div>

        <form action="{{ route('quotation.form', $product) }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                <input type="text" name="customer_name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your full name">
                @error('customer_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="your@email.com">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Message / Specifications *</label>
                <textarea name="message" rows="6" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical" placeholder="Tell us about your requirements, quantity, delivery location, or any special instructions..."></textarea>
                @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl text-center font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl text-lg transition duration-200">
                <i class="fas fa-paper-plane mr-2"></i> Submit Quotation Request
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-500">
            <p>We will contact you within 24 hours with a detailed quotation. Your information is secure and private.</p>
        </div>
    </div>
</div>
@endsection
