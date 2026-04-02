@extends('admin.layouts.app')

@section('title', 'Quotation Details')

@section('content')
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
                        View Product
                    </a>
                </div>
            </div>
        </div>

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
            </div>
        </div>
    </div>

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
                        Delete
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
