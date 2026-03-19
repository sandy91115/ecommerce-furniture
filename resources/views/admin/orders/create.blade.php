@extends('admin.layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>

    <form method="POST" action="{{ route('admin.orders.store') }}" class="bg-white shadow-lg rounded-xl p-8">
        @csrf
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Create New Order</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-blue-500"></i>User *
                </label>
                <select name="user_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 ring-2 ring-red-500/50 @enderror">
                    <option value="">Select User</option>
@php $customers = \App\Models\User::role('customer')->get() ?: \App\Models\User::whereDoesntHave('roles')->get(); @endphp @foreach($customers as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-store mr-2 text-green-500"></i>Vendor (Optional)
                </label>
                <select name="vendor_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">No Vendor</option>
                    @foreach(\App\Models\Vendor::all() as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->store_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
            <input type="number" name="total_amount" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            @error('total_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
            <textarea name="shipping_address" rows="4" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
            @error('shipping_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Items JSON (e.g. [{"product_id":1,"qty":2,"price":99.99}])</label>
            <textarea name="items" rows="4" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder='[{"product_id":1,"qty":1,"price":99.99}]'></textarea>
            @error('items') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition duration-300">
            <i class="fas fa-save mr-2"></i> Create Order
        </button>
    </form>
</div>
@endsection

