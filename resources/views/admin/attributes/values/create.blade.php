@extends('admin.layouts.app')

@section('title', $attribute->name . ' - Create Value')

@section('content')
<div class="max-w-md">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create Value for {{ $attribute->name }}</h1>
        <a href="{{ route('admin.attributes.values', $attribute) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
            Back to Values
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-8">
        <form action="{{ route('admin.attributes.values.store', $attribute) }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Value *</label>
                <input type="text" name="value" value="{{ old('value') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('value') border-red-500 @enderror">
                @error('value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if(strtolower($attribute->slug) === 'color' || strtolower($attribute->name) === 'color')
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color Code (Hex)</label>
                    <input type="text" name="color_code" value="{{ old('color_code') }}" placeholder="#FF0000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color_code') border-red-500 @enderror">
                    @error('color_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Example: #FF0000</p>
                </div>
            @endif

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                    Create Value
                </button>
                <a href="{{ route('admin.attributes.values', $attribute) }}" class="bg-gray-400 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
