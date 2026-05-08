@extends('admin.layouts.app')

@section('title', 'Quotation Details')

@section('content')
<<<<<<< HEAD
@php
    $statusClasses = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'contacted' => 'bg-blue-100 text-blue-800',
        'closed' => 'bg-green-100 text-green-800',
    ];

    $productImage = $quotation->product->images->first()
        ? asset('storage/' . $quotation->product->images->first()->path)
        : asset('assets/img/product/default.jpg');
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Quotation #{{ $quotation->id }}</h1>
            <p class="mt-1 text-sm text-gray-600">Details of customer inquiry.</p>
        </div>
        <a href="{{ route('admin.quotations.index') }}" class="inline-flex w-max items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-gray-700">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <div class="rounded-lg bg-white shadow xl:col-span-4">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-900">Product Details</h2>
            </div>
            <div class="p-6">
                <img src="{{ $productImage }}" alt="{{ $quotation->product->name }}" class="h-64 w-full rounded-lg object-cover">
                <div class="mt-5">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $quotation->product->name }}</h3>
                    <p class="mt-2 text-sm font-semibold text-gray-700">{{ currency($quotation->product->sale_price ?? $quotation->product->price) }}</p>
                    <a href="{{ route('product-details', $quotation->product->slug) }}" target="_blank" class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700">
=======
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Quotation #{{ $quotation->id }}</h1>
            <p class="mt-1 text-sm text-gray-600">Details of customer inquiry</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.quotations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white hover:bg-gray-700 rounded-lg font-medium transition-colors">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Product Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Product Details</h3>
            <div class="space-y-4">
                <div>
                    <img src="{{ $quotation->product->images->first() ? asset('storage/' . $quotation->product->images->first()->path) : asset('assets/img/product/default.jpg') }}" alt="{{ $quotation->product->name }}" class="w-full h-64 object-cover rounded-xl shadow-md">
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $quotation->product->name }}</h4>
                    <p class="text-gray-600 dark:text-gray-400">{{ currency($quotation->product->sale_price ?? $quotation->product->price) }}</p>
                    <a href="{{ route('product-details', $quotation->product->slug) }}" target="_blank" class="inline-flex items-center mt-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-medium text-sm transition-colors">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                        View Product
                    </a>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <div class="rounded-lg bg-white shadow xl:col-span-8">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-900">Customer Details</h2>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2 xl:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Name</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $quotation->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Email</dt>
                        <dd class="mt-1 break-all text-base font-semibold text-gray-900">
                            <a href="mailto:{{ $quotation->email }}" class="text-blue-600 hover:text-blue-800">{{ $quotation->email }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Phone</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $quotation->phone ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">City</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $quotation->city ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Country</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $quotation->country ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pincode</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $quotation->pincode ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Desired Price</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ currency($quotation->desired_price ?? 0) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Status</dt>
                        <dd class="mt-2">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses[$quotation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Submitted</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $quotation->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div class="md:col-span-2 xl:col-span-3">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Message</dt>
                        <dd class="mt-2 rounded-lg border border-gray-100 bg-gray-50 p-4 text-sm leading-6 text-gray-800 whitespace-pre-wrap">{{ filled($quotation->message) ? $quotation->message : 'No message provided' }}</dd>
                    </div>
                </dl>
=======
        <!-- Customer Info & Form Data -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Customer Details</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Name</label>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $quotation->customer_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <p class="text-lg font-medium text-gray-900 dark:text-white"><a href="mailto:{{ $quotation->email }}" class="hover:underline">{{ $quotation->email }}</a></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $quotation->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Desired Price</label>
                    <p class="text-lg font-semibold text-primary">{{ currency($quotation->desired_price ?? 0) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Message</label>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ filled($quotation->message) ? $quotation->message : 'No message provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                        {{ ucfirst($quotation->status) }}
                    </span>
                </div>
                <div class="pt-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Submitted</label>
                    <p class="text-gray-500 dark:text-gray-400">{{ $quotation->created_at->format('M d, Y \\a\\t g:i A') }}</p>
                </div>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            </div>
        </div>
    </div>

<<<<<<< HEAD
    <div class="rounded-lg bg-white shadow">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-lg font-bold text-gray-900">Update Status</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.quotations.update', $quotation) }}" method="POST" class="grid grid-cols-1 gap-4 lg:grid-cols-[1fr_auto] lg:items-end">
                @csrf
                @method('PUT')
                <div>
                    <label for="quotation-status" class="mb-2 block text-sm font-semibold text-gray-700">Status</label>
                    <select id="quotation-status" name="status" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <option value="pending" {{ $quotation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="contacted" {{ $quotation->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="closed" {{ $quotation->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700">
                    Update Status
                </button>
            </form>

            <div class="mt-6 border-t border-gray-100 pt-6">
                <form action="{{ route('admin.quotations.destroy', $quotation) }}" method="POST" onsubmit="return confirm('Delete this quotation?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex rounded-md bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-red-700">
=======
    <!-- Status Update Form -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Update Status</h3>
        <form action="{{ route('admin.quotations.update', $quotation) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition-all">
                    <option value="pending" {{ $quotation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="contacted" {{ $quotation->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="closed" {{ $quotation->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl">
                    Update Status
                </button>
                <form action="{{ route('admin.quotations.destroy', $quotation) }}" method="POST" class="inline" onsubmit="return confirm('Delete this quotation?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                        Delete
                    </button>
                </form>
            </div>
<<<<<<< HEAD
        </div>
=======
        </form>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    </div>
</div>
@endsection
