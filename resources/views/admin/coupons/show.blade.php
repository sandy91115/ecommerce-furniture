@extends('admin.layouts.app')

@section('title', 'Coupon Details - {{ $coupon->code }}')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $coupon->code }}</h1>
            <p class="text-xl text-gray-600 mt-1">Coupon Details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-white hover:bg-yellow-700">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Coupons
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white shadow rounded-lg p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 border-b pb-4">Basic Information</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                    <div class="text-2xl font-bold text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">{{ $coupon->code }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-{{ $coupon->type === 'percentage' ? 'green' : 'blue' }}-100 text-{{ $coupon->type === 'percentage' ? 'green' : 'blue' }}-800">
                            {{ ucfirst($coupon->type) }} {{ $coupon->type === 'percentage' ? $coupon->value.'%' : '$'.number_format($coupon->value, 2) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $coupon->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $coupon->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Uses</label>
                        <div class="text-lg text-gray-900">{{ $coupon->used_count }} / {{ $coupon->max_uses }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valid Period</label>
                        <div class="text-lg text-gray-900">
                            {{ $coupon->valid_from->format('M d, Y H:i') }} - {{ $coupon->valid_until->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 border-b pb-4">Usage Rules</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Amount</label>
                    <div class="text-lg text-gray-900">${{ number_format($coupon->min_order_amount ?? 0, 2) }}</div>
                </div>
                @if($coupon->max_discount_amount)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Discount Amount</label>
                    <div class="text-lg text-gray-900">${{ number_format($coupon->max_discount_amount, 2) }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

