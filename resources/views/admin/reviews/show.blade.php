@extends('admin.layouts.app')

@section('title', 'Review Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Review Details</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Review #{{ $review->id }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reviews.edit', $review) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Edit Review</a>
            <a href="{{ route('admin.reviews.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-white dark:hover:bg-gray-800">Back</a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900 lg:col-span-2">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $review->title ?: 'Untitled Review' }}</h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Submitted on {{ $review->created_at?->format('M d, Y h:i A') }}</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $review->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : ($review->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200') }}">
                    {{ ucfirst($review->status) }}
                </span>
            </div>

            <div class="mt-6">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Comment</h3>
                <div class="mt-3 rounded-xl bg-gray-50 p-4 text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    {{ $review->comment ?: 'No comment provided.' }}
                </div>
            </div>

            @php
                $imagePaths = is_array($review->images) ? $review->images : [];
            @endphp
            @if (! empty($imagePaths))
                <div class="mt-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Images</h3>
                    <div class="mt-3 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($imagePaths as $imagePath)
                            <a href="{{ asset('storage/' . $imagePath) }}" target="_blank" rel="noopener" class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                                <img src="{{ asset('storage/' . $imagePath) }}" alt="Review image" class="h-48 w-full object-cover">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Review Meta</h3>
                <dl class="mt-4 space-y-4 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Product</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                            @if ($review->product)
                                <a href="{{ route('product-details', $review->product->slug) }}" class="text-blue-600 hover:underline dark:text-blue-400">{{ $review->product->name }}</a>
                            @else
                                Deleted product
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">User</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $review->user->name ?? 'Guest' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $review->user->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Rating</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $review->rating }}/5</dd>
                    </div>
                </dl>
            </div>

            @if ($review->status === 'pending')
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Moderation</h3>
                    <div class="mt-4 flex gap-3">
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">Approve</button>
                        </form>
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700">Reject</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
