@extends('admin.layouts.app')

@section('title', 'Edit Order')

@section('content')
<<<<<<< HEAD
<div >
=======
<div class="max-w-2xl">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>

    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="bg-white shadow-lg rounded-xl p-8">
        @csrf @method('PUT')
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Edit Order #{{ $order->order_number }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                <p class="p-3 bg-gray-50 rounded-xl">{{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? 'N/A' }})</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                <p class="p-3 bg-gray-50 rounded-xl">{{ $order->vendor->store_name ?? '-' }}</p>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
            <p class="p-3 bg-gray-50 rounded-xl font-bold text-2xl">{{ currency($order->total_amount) }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
<<<<<<< HEAD
                    @foreach(\App\Enums\OrderStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status', $order->status?->value) === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                    @endforeach
=======
                    <option value="pending" {{ $order->status->value == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status->value == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status->value == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status->value == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status->value == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                </select>
                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
<<<<<<< HEAD
                    @foreach(\App\Models\Order::PAYMENT_STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ old('payment_status', $order->payment_status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
=======
                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                </select>
                @error('payment_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
            <textarea name="shipping_address" rows="3" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $order->shipping_address }}</textarea>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Items</label>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                    <img src="{{ image_url($item['image'] ?? null, fallback: asset('assets/img/no-image.png')) }}" alt="{{ $item['name'] ?? 'Product' }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900">{{ $item['name'] ?? 'Product' }}</h4>
                        @if(isset($item['sku']))
                            <p class="text-sm text-gray-600 mb-1">SKU: {{ $item['sku'] }}</p>
                        @endif
                        @if(!empty($item['attributes']))
                            <div class="flex flex-wrap gap-1 mb-2">
                                @foreach($item['attributes'] as $attr)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $attr['attribute_name'] ?? '' }}: {{ $attr['value'] ?? '' }}</span>
                                @endforeach
                            </div>
                        @endif
                        <p class="text-sm text-gray-600">Qty: {{ $item['quantity'] ?? 0 }} × {{ currency($item['price'] ?? 0) }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold">{{ currency(($item['quantity'] ?? 0) * ($item['price'] ?? 0)) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition duration-300">
            <i class="fas fa-save mr-2"></i> Update Order
        </button>
    </form>
</div>
@endsection
