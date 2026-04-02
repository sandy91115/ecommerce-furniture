@extends('admin.layouts.app')

@section('title', 'Edit Partner')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Partner</h1>
            <p class="text-gray-600 mt-1">{{ $partner->name }}</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('admin.partners.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>All Partners
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-8">
        <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Partner Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $partner->name) }}" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <input type="number" name="order" value="{{ old('order', $partner->order) }}" min="0" max="999"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Partner Logo</label>
                <div class="space-y-4">
                    @if($partner->image)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg border">
                            <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" class="w-24 h-24 object-contain rounded-lg border flex-shrink-0">
                            <div>
                                <p class="font-medium text-gray-900">Current: {{ basename($partner->image) }}</p>
                                <p class="text-sm text-gray-500">Replace below to change</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 focus:ring-2 focus:ring-blue-500 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-8-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload new image</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">or drag & drop (Optional)</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, SVG, WEBP up to 2MB</p>
                        </div>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Partner URL (Optional)</label>
                <input type="url" name="url" value="{{ old('url', $partner->url) }}" placeholder="https://partner-website.com"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('url') border-red-500 @enderror">
                @error('url')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Website URL where logo links to</p>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ old('status', $partner->status) === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                    <option value="inactive" {{ old('status', $partner->status) === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>
            </div>

            <div class="mt-8 flex space-x-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 font-medium">
                    <i class="fas fa-save mr-2"></i>Update Partner
                </button>
                <a href="{{ route('admin.partners.index') }}" class="flex-1 bg-gray-200 text-gray-900 py-3 px-6 rounded-lg hover:bg-gray-300 font-medium text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

