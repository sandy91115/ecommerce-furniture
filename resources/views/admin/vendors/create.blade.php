@extends('admin.layouts.app')

@section('title', 'Create Vendor')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.vendors.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Vendors
        </a>
    </div>

    <form method="POST" action="{{ route('admin.vendors.store') }}" enctype="multipart/form-data" class="bg-white shadow-lg rounded-xl p-8">
        @csrf
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Create New Vendor</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-blue-500"></i>Owner Name *
                </label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 ring-2 ring-red-500/50 @enderror" required>
                @error('name') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-green-500"></i>Email *
                </label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 ring-2 ring-red-500/50 @enderror" required>
                @error('email') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
            <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full p-3 mt-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Store Name *</label>
            <input type="text" name="store_name" value="{{ old('store_name') }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            @error('store_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Store Slug * (yourstore.furnixar.com/{slug})</label>
            <div class="flex">
                <span class="inline-flex items-center px-4 py-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-sm">yourstore.furnixar.com/</span>
                <input type="text" name="store_slug" value="{{ old('store_slug') }}" class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            @error('store_slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Logo</label>
                <input type="file" name="store_logo" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Banner</label>
                <input type="file" name="store_banner" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Store Description *</label>
            <textarea name="store_description" rows="4" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('store_description') }}</textarea>
            @error('store_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Address</label>
                <textarea name="store_address" rows="3" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('store_address') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Phone</label>
                <input type="text" name="store_phone" value="{{ old('store_phone') }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <button type="submit" class="w-full mt-8 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition duration-300">
            <i class="fas fa-save mr-2"></i> Create Vendor
        </button>
    </form>
</div>
@endsection

