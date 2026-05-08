@extends('admin.layouts.app')

@section('title', 'Quotation Details')

@section('content')
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
                        View Product
                    </a>
                </div>
            </div>
        </div>

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
            </div>
        </div>
    </div>

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
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
