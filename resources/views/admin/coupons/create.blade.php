@extends('admin.layouts.app')

@section('title', 'Create Coupon')

@section('content')
<div >
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Create New Coupon</h2>
        <p class="text-gray-600">Create a new coupon code for marketing campaigns.</p>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white shadow rounded-lg p-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Coupon Code</label>
                <input type="text" name="code" id="code" value="{{ old('code') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror" placeholder="WELCOME10">
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" id="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="value" class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                <input type="number" step="0.01" name="value" id="value" value="{{ old('value') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('value') border-red-500 @enderror">
                @error('value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-2">Max Uses</label>
                <input type="number" name="max_uses" id="max_uses" value="{{ old('max_uses', 1) }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('max_uses') border-red-500 @enderror">
                @error('max_uses')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="min_order_amount" class="block text-sm font-medium text-gray-700 mb-2">Min Order Amount</label>
                <input type="number" step="0.01" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('min_order_amount') border-red-500 @enderror">
                @error('min_order_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="max_discount_amount" class="block text-sm font-medium text-gray-700 mb-2">Max Discount Amount (Optional)</label>
                <input type="number" step="0.01" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('max_discount_amount') border-red-500 @enderror">
                @error('max_discount_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-2">Valid From</label>
                <input type="datetime-local" name="valid_from" id="valid_from" value="{{ old('valid_from') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('valid_from') border-red-500 @enderror">
                @error('valid_from')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-2">Valid Until</label>
                <input type="datetime-local" name="valid_until" id="valid_until" value="{{ old('valid_until') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('valid_until') border-red-500 @enderror">
                @error('valid_until')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="status" class="flex items-center">
                <input type="checkbox" name="status" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
            </label>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-3">
            <button type="submit" class="flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                <i class="fas fa-save mr-2"></i> Create Coupon
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2 border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

