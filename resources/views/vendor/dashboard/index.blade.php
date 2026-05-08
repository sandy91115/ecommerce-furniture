@extends('vendor.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Products -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-box text-2xl text-blue-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-shopping-cart text-2xl text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
            </div>
        </div>
    </div>

    <!-- Total Earnings -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
                <i class="fas fa-dollar-sign text-2xl text-yellow-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($totalEarnings, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Store Status -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Store Status</p>
                <p class="text-3xl font-bold {{ $vendor->status == 'active' ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ ucfirst($vendor->status) }}
                </p>
            </div>
            <div class="p-3 bg-gray-100 rounded-lg">
                <i class="fas fa-store text-xl text-gray-700"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-md border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse ($recentOrders as $order)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">#{{ $order->id }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                                <span class="ml-2">{{ $order->order_status }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($order->items->sum('total'), 2) }}</p>
                            <p class="text-sm text-gray-500">{{ $order->items->count() }} items</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-shopping-cart text-4xl mb-4 opacity-25"></i>
                    <p class="text-lg">No recent orders</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6 space-y-4">
            <a href="{{ route('vendor.products.create') }}" class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-plus-circle text-2xl text-blue-600 mr-4"></i>
                <div>
                    <p class="font-medium text-gray-900">Add New Product</p>
                    <p class="text-sm text-blue-600">Start selling now</p>
                </div>
            </a>
            <a href="{{ route('vendor.profile.edit') }}" class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-store text-2xl text-green-600 mr-4"></i>
                <div>
                    <p class="font-medium text-gray-900">Edit Store Profile</p>
                    <p class="text-sm text-green-600">Update your store info</p>
                </div>
            </a>
            <a href="{{ route('vendor.orders.index') }}" class="flex items-center p-4 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-list text-2xl text-purple-600 mr-4"></i>
                <div>
                    <p class="font-medium text-gray-900">View All Orders</p>
                    <p class="text-sm text-purple-600">Manage your orders</p>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection

