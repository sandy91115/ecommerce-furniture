@extends('admin.layouts.app')

@section('title', $vendor->store_name)

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $vendor->store_name }}</h1>
            <p class="text-xl text-gray-600">Store Dashboard</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('admin.vendors.edit', $vendor) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold">
                Edit
            </a>
            <a href="/store/{{ $vendor->store_slug }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold">
                <i class="fas fa-external-link-alt mr-2"></i> View Store
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Store Info -->
        <div class="bg-white shadow-lg rounded-xl p-8">
            <h3 class="text-2xl font-bold mb-6">Store Information</h3>
            <div class="space-y-6">
                <div class="flex items-start space-x-4">
                    <img src="{{ $vendor->store_logo ? asset('storage/' . $vendor->store_logo) : asset('assets/img/icon/store-placeholder.png') }}" alt="Logo" class="w-24 h-24 rounded-xl object-cover flex-shrink-0">
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-900">{{ $vendor->store_name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($vendor->store_description, 100) }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 mr-2">yourstore.furnixar.com/</span>
                        <a href="/store/{{ $vendor->store_slug }}" class="font-mono font-semibold text-blue-600 hover:text-blue-800">{{ $vendor->store_slug }}</a>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span class="px-3 py-1 ml-2 text-xs font-semibold rounded-full {{ $vendor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Products:</span>
                        <span class="font-semibold text-gray-900 ml-2">{{ $vendor->products()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Owner Info -->
        <div class="bg-white shadow-lg rounded-xl p-8">
            <h3 class="text-2xl font-bold mb-6">Owner Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <p class="font-semibold">{{ $vendor->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <p>{{ $vendor->user->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <p>{{ $vendor->store_phone ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <p class="whitespace-pre-wrap">{{ $vendor->store_address ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner & Quick Stats -->
    @if($vendor->store_banner)
    <div class="mb-8">
        <label class="block text-lg font-semibold text-gray-900 mb-4">Store Banner</label>
        <img src="{{ asset('storage/' . $vendor->store_banner) }}" alt="Banner" class="w-full h-48 object-cover rounded-xl shadow-lg">
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white shadow-lg rounded-xl p-6 flex space-x-4">
        <a href="{{ route('admin.vendors.edit', $vendor) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-4 px-8 rounded-xl text-center font-semibold">
            Edit Store
        </a>
        <button onclick="window.open('/store/{{ $vendor->store_slug }}', '_blank')" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-4 px-8 rounded-xl text-center font-semibold">
            View Live Store
        </button>
    </div>
</div>
@endsection

