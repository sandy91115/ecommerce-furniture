@extends('vendor.layouts.app')

@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
        <a href="{{ route('vendor.products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Products
        </a>
    </div>

    <div class="bg-white shadow-2xl rounded-3xl p-10">
        <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500 transition-all">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Category *</label>
                        <select name="category_id" required class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">SKU *</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required 
                               class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500">
                        @error('sku')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">Price ($)*</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required min="0"
                                   class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500">
                            @error('price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">Sale Price ($)</label>
                            <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" min="0"
                                   class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500">
                            @error('sale_price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" 
                               class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500">
                        @error('stock')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Status</label>
                        <select name="status" required class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Short Description *</label>
                        <textarea name="short_description" rows="4" required class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500 resize-vertical">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Full Description *</label>
                        <textarea name="description" rows="8" required class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-3 focus:ring-blue-200 focus:border-blue-500 resize-vertical">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Product Images * (Min 1)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-blue-400 transition-colors">
                            <input type="file" name="images[]" multiple required 
                                   class="absolute opacity-0 w-full h-full cursor-pointer">
                            <div class="space-y-2">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mx-auto block"></i>
                                <p class="text-lg font-medium text-gray-900">Click to upload or drag & drop</p>
                                <p class="text-sm text-gray-500">PNG, JPG up to 2MB each</p>
                            </div>
                        </div>
                        @error('images')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-8 border-t border-gray-200 mt-8">
                <a href="{{ route('vendor.products.index') }}" class="mr-4 px-8 py-4 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl font-medium px-8 py-4">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-12 py-4 rounded-xl font-bold text-lg shadow-2xl hover:shadow-3xl transition-all">
                    <i class="fas fa-save mr-2"></i>Create Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

