@extends('admin.layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Reviews Management</h1>
        <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus mr-2"></i>New Review
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Reviews</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Product</th>
                            <th>Avg Rating</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <div>
                                        <h6>{{ $review->user->name ?? 'Guest' }}</h6>
                                        <small class="text-muted">{{ $review->user->email ?? '-' }}</small>
                                    </div>
                                </td>
                                <td><a href="{{ route('product-details', $review->product->slug) }}" class="font-medium hover:text-primary transition-colors">{{ Str::limit($review->product->name, 30) }}</a></td>
                                <td class="font-semibold text-primary">{{ number_format($review->product->reviews()->where('status', 'approved')->avg('rating'), 1) ?? 'N/A' }}</td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" viewBox="0 0 15 14" fill="currentColor">
                                                <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm font-medium text-gray-700">({{ $review->rating }}/5)</span>
                                    </div>
                                </td>
                                <td>{{ Str::limit($review->comment ?? $review->title, 80) }}</td>
                                <td>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                        {{ $review->status == 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                           ($review->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                           'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $review->created_at->format('M d, Y') }}<br>
                                    <small class="text-gray-500">{{ $review->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="whitespace-nowrap">

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.reviews.show', $review) }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-all text-slate-500 hover:text-blue-600" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @if($review->status == 'pending')
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="p-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 hover:text-emerald-800 rounded-lg transition-all" title="Approve">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="p-2 bg-amber-100 hover:bg-amber-200 text-amber-700 hover:text-amber-800 rounded-lg transition-all" title="Reject">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.reviews.edit', $review) }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-all text-slate-500 hover:text-blue-600" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 text-red-500 hover:text-red-600 rounded-lg transition-all" onclick="return confirm('Delete this review?')" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-20 h-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915a1 1 0 00.95-.69l1.519-4.674c.3-.921 1.603-.921 1.902 0l3.99 12.292a1 1 0 00.95.69h5.96a1 1 0 00.95-.69L24.04 2.927c-.3-.921-1.603-.921-1.902 0l-3.99 12.292a1 1 0 00-.95.69H9.473a1 1 0 00-.95-.69L4.973 3.927c-.3-.921-1.603-.921-1.902 0l3.076 9.365a1 1 0 00.95.69h4.915a1 1 0 00.95-.69L11.049 2.927z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" colspan="8" stroke-width="1.5" d="M20 18a3 3 0 100-6 3 3 0 000 6z"></path>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Reviews Yet</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-6">There are no reviews to display.</p>
                                        <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Create First Review
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection


