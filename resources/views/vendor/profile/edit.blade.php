@extends('vendor.layouts.app')

@section('page-title', 'Store Profile')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Store Profile</h1>
    </div>

    <div class="bg-white shadow-xl rounded-2xl p-8">
        <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Store Name</label>
                    <input type="text" name="store_name" value="{{ old('store_name', $vendor->store_name) }}" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('store_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Store Slug</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 py-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-sm">yourstore.furnixar.com/</span>
                        <input type="text" name="store_slug" value="{{ old('store_slug', $vendor->store_slug) }}" required 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('store_slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Store Logo</label>
                        <input type="file" name="store_logo" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($vendor->store_logo)
                            <img src="{{ asset('storage/' . $vendor->store_logo) }}" alt="Logo" class="mt-2 h-24 w-24 object-cover rounded-xl border-2 border-gray-200">
                        @endif
                        @error('store_logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Store Banner</label>
                        <input type="file" name="store_banner" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($vendor->store_banner)
                            <img src="{{ asset('storage/' . $vendor->store_banner) }}" alt="Banner" class="mt-2 h-24 w-48 object-cover rounded-xl border-2 border-gray-200">
                        @endif
                        @error('store_banner')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Store Description</label>
                    <textarea name="store_description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('store_description', $vendor->store_description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Store Address</label>
                        <textarea name="store_address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('store_address', $vendor->store_address) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Store Phone</label>
                        <input type="text" name="store_phone" value="{{ old('store_phone', $vendor->store_phone) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all text-lg">
                        <i class="fas fa-save mr-2"></i>Update Store Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

