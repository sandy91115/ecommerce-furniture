@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600 mt-2">Placed on {{ $order->created_at->format('M d, Y \\a\\t g:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-2 bg-white shadow-lg rounded-xl p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Order Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                    <p class="text-lg font-semibold">{{ $order->user->name ?? 'N/A' }}</p>
                    <p class="text-gray-600">{{ $order->user->email ?? 'N/A' }}</p>
                </div>
                @if($order->vendor)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                    <p class="text-lg font-semibold">{{ $order->vendor->store_name }}</p>
                    <p class="text-gray-600">{{ $order->vendor->store_slug }}</p>
                </div>
                @endif
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex justify-between">
                    <span class="text-gray-700">Status:</span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $order->status->badge() }}">
                        {{ $order->status->label() }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Payment Status:</span>
<<<<<<< HEAD
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $order->paymentStatusBadge() }}">
                        {{ $order->paymentStatusLabel() }}
=======
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($order->payment_status) }}
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                    </span>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-lg font-semibold text-gray-900 mb-3">Shipping Address</label>
                <div class="p-4 bg-gray-50 rounded-xl border">
                    {{ $order->shipping_address }}
                </div>
            </div>

<<<<<<< HEAD
<div>
=======
            <div>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                <label class="block text-lg font-semibold text-gray-900 mb-3">Order Items</label>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
<<<<<<< HEAD
                        @php
                        $productImage = $item['image'] ?? null;
                        $itemTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                        @endphp
                        <img src="{{ $productImage ? asset('storage/' . $productImage) : asset('assets/img/no-image.png') }}" alt="{{ $item['name'] ?? 'Product' }}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900">{{ $item['name'] ?? 'Product' }}</h4>
                            @if(isset($item['slug']))
                                <p class="text-sm text-gray-600 mb-1">Slug: {{ $item['slug'] ?? 'N/A' }}</p>
                            @endif
                            @if(isset($item['attributes']) && $item['attributes'])
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($item['attributes'] as $attr)
=======
                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('assets/img/no-image.png') }}" alt="{{ $item->product_name ?? 'Product' }}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900">{{ $item->product_name ?? 'Product' }}</h4>
                            @if(isset($item['sku']))
                                <p class="text-sm text-gray-600 mb-1">SKU: {{ $item->product_sku ?? 'N/A' }}</p>
                            @endif
                            @if($item->variation_data)
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($item->variation_data as $attr)
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $attr['attribute_name'] ?? '' }}: {{ $attr['value'] ?? '' }}</span>
                                    @endforeach
                                </div>
                            @endif
<<<<<<< HEAD
                            <p class="text-sm text-gray-600">Qty: {{ $item['quantity'] ?? 1 }} × {{ currency($item['price'] ?? 0) }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-lg">{{ currency($itemTotal) }}</p>
=======
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × {{ currency($item->price) }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-lg">{{ currency($item->total) }}</p>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-4">
            <a href="{{ route('admin.orders.edit', $order) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-4 px-6 rounded-xl font-semibold shadow-lg transition duration-300">
                <i class="fas fa-edit mr-2"></i> Edit Status
            </a>
            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="block" onsubmit="return confirm('Are you sure you want to delete this order?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-center py-4 px-6 rounded-xl font-semibold shadow-lg transition duration-300">
                    <i class="fas fa-trash mr-2"></i> Delete Order
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
