@extends('admin.layouts.app')

@section('title', 'Edit Vendor')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.vendors.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Vendors
        </a>
    </div>

    <form method="POST" action="{{ route('admin.vendors.update', $vendor) }}" enctype="multipart/form-data" class="bg-white shadow-lg rounded-xl p-8">
        @csrf @method('PUT')
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Edit {{ $vendor->store_name }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Owner</label>
                <p class="p-3 bg-gray-50 rounded-xl">{{ $vendor->user->name ?? 'N/A' }} ({{ $vendor->user->email ?? 'N/A' }})</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Products Count</label>
                <p class="p-3 bg-gray-50 rounded-xl font-bold text-green-600">0</p>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Store Name *</label>
            <input type="text" name="store_name" value="{{ old('store_name', $vendor->store_name) }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            @error('store_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Store Slug * (yourstore.furnixar.com/{slug})</label>
            <div class="flex">
                <span class="inline-flex items-center px-4 py-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-sm">yourstore.furnixar.com/</span>
                <input type="text" name="store_slug" value="{{ old('store_slug', $vendor->store_slug) }}" class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            @error('store_slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                @if($vendor->store_logo)
                    <img src="{{ asset('storage/' . $vendor->store_logo) }}" alt="Logo" class="w-24 h-24 object-cover rounded-xl border-2 border-gray-200 mb-2">
                    <input type="file" name="store_logo" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                @else
                    <input type="file" name="store_logo" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Banner</label>
                @if($vendor->store_banner)
                    <img src="{{ asset('storage/' . $vendor->store_banner) }}" alt="Banner" class="w-48 h-24 object-cover rounded-xl border-2 border-gray-200 mb-2">
                    <input type="file" name="store_banner" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                @else
                    <input type="file" name="store_banner" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                @endif
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Store Description *</label>
            <textarea name="store_description" rows="4" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('store_description', $vendor->store_description) }}</textarea>
            @error('store_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Status *</label>
                <select name="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="active" {{ old('status', $vendor->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ old('status', $vendor->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="inactive" {{ old('status', $vendor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Phone</label>
                <input type="text" name="store_phone" value="{{ old('store_phone', $vendor->store_phone) }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Store Address</label>
            <textarea name="store_address" rows="3" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('store_address', $vendor->store_address) }}</textarea>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition duration-300">
            <i class="fas fa-save mr-2"></i> Update Vendor
        </button>
    </form>
</div>
@endsection

