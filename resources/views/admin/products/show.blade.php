@extends('admin.layouts.app')

@section('title', 'Product Details - ' . $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Product Details</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $product->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-medium">
                ← Back to Products
            </a>
            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-medium">
                Edit Product
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Images Gallery -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Product Images</h2>
            @if($product->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    @foreach($product->images as $image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->alt }}" class="w-full h-48 object-cover rounded-lg shadow-md group-hover:shadow-lg transition-shadow duration-200">
                            @if($image->featured)
                                <span class="absolute top-2 right-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-2">No images uploaded</p>
                </div>
            @endif
        </div>

        <!-- Basic Info -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-6 sm:gap-x-6 sm:gap-y-4">
                    <div class="sm:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Product ID</dt>
                        <dd class="text-sm text-gray-900 font-semibold">{{ $product->id }}</dd>
                    </div>
                    <div class="sm:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Name</dt>
                        <dd class="text-sm text-gray-900 font-semibold">{{ $product->name }}</dd>
                    </div>
                    <div class="sm:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 mb-1">SKU</dt>
                        <dd class="text-sm text-gray-900">{{ $product->sku ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Status</dt>
                        <dd class="text-sm">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </dd>
                    </div>
                    <div class="sm:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Category</dt>
                        <dd class="text-sm text-gray-900">{{ $product->category->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Vendor</dt>
                        <dd class="text-sm text-gray-900">{{ $product->vendor->store_name ?? 'Direct Admin' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Pricing & Stock -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing & Inventory</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Regular Price</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ currency($product->price) }}</dd>
                    </div>
                    @if($product->sale_price)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Sale Price</dt>
                        <dd class="text-2xl font-bold text-green-600 line-through">{{ currency($product->sale_price) }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Stock</dt>
                        <dd class="text-2xl font-bold {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">{{ $product->stock }}</dd>
                    </div>
                    @if($product->featured)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Featured</dt>
                        <dd class="text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Yes</span>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <!-- Full Details -->
    <div class="mt-8 bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Additional Details</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($product->short_description)
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-2">Short Description</dt>
                    <dd class="text-sm text-gray-900">{!! nl2br(e($product->short_description)) !!}</dd>
                </div>
                @endif
                @if($product->description)
                <div class="lg:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Full Description</dt>
                    <dd class="text-sm text-gray-900 prose max-w-none">{!! $product->description !!}</dd>
                </div>
                @endif
                @if($product->dimensions)
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-2">Dimensions</dt>
                    <dd class="text-sm text-gray-900">{{ collect($product->dimensions)->map(fn($v, $k) => ucfirst($k) . ': ' . $v)->implode(', ') }}</dd>
                </div>
                @endif
                @if($product->weight)
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-2">Weight</dt>
                    <dd class="text-sm text-gray-900">{{ $product->weight }} kg</dd>
                </div>
                @endif
                @if($product->material_id)
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-2">Material</dt>
                    <dd class="text-sm text-gray-900">{{ $product->material->name ?? 'N/A' }}</dd>
                </div>
                @endif
                @if($product->color_id)
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-2">Color</dt>
                    <dd class="text-sm text-gray-900">{{ $product->color->name ?? 'N/A' }}</dd>
                </div>
                @endif
                @if($product->warranty_months)
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-2">Warranty</dt>
                    <dd class="text-sm text-gray-900">{{ $product->warranty_months }} months</dd>
                </div>
                @endif
                @if($product->assembly_required)
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-2">Assembly</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Required</span>
                    </dd>
                </div>
                @endif
                @if($product->seo_title || $product->seo_description)
                <div class="lg:col-span-3">
                    <dt class="text-sm font-medium text-gray-500 mb-2">SEO</dt>
                    <dd class="text-sm text-gray-900">
                        @if($product->seo_title)<strong>Title:</strong> {{ $product->seo_title }}<br>@endif
                        @if($product->seo_description)<strong>Description:</strong> {{ Str::limit($product->seo_description, 160) }} @endif
                    </dd>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
