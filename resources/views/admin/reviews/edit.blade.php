@extends('admin.layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Edit Review</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left mr-2"></i>Back to Reviews
        </a>
    </div>

    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="form-label">Product *</label>
                <select name="product_id" class="form-control" required>
                    <option value="">Select Product</option>
                    @foreach(\App\Models\Product::where('status', 'active')->get() as $product)
                        <option value="{{ $product->id }}" {{ $review->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">User</label>
                <select name="user_id" class="form-control">
                    <option value="">Guest</option>
                    @foreach(\App\Models\User::take(50)->get() as $user)
                        <option value="{{ $user->id }}" {{ $review->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">Customer Name</label>
                <input type="text" name="reviewer_name" class="form-control" value="{{ old('reviewer_name', $review->reviewer_name) }}" placeholder="Shown on frontend">
                @error('reviewer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">Customer Email</label>
                <input type="email" name="reviewer_email" class="form-control" value="{{ old('reviewer_email', $review->reviewer_email) }}" placeholder="Optional">
                @error('reviewer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">Rating *</label>
                <div class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" name="rating" value="{{ $i }}" id="admin-star{{ $i }}{{ $review->id }}" class="sr-only" required {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                        <label for="admin-star{{ $i }}{{ $review->id }}" class="admin-star cursor-pointer transition-all hover:scale-110" data-rating="{{ $i }}">
                            <svg class="w-8 h-8 fill-current text-yellow-400 hover:text-yellow-500" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                            </svg>
                        </label>
                    @endfor
                </div>
                @error('rating') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">Status *</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ old('status', $review->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ old('status', $review->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ old('status', $review->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $review->title) }}">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Comment</label>
                <textarea name="comment" class="form-control" rows="5">{{ old('comment', $review->comment) }}</textarea>
                @error('comment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Images (Optional)</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                @error('images') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex gap-4 mt-8">
            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-content-save mr-2"></i>Update Review
            </button>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Admin Star Rating Handler (shared for create/edit)
    const adminStars = document.querySelectorAll('.admin-star');
    const ratingRadios = document.querySelectorAll('input[name="rating"]');
    let currentAdminRating = {{ old('rating', $review->rating ?? 0) }};

    adminStars.forEach((star, index) => {
        star.addEventListener('mouseover', () => {
            adminStars.forEach((s, i) => s.classList.toggle('filled', i <= index));
        });
        star.addEventListener('mouseout', () => {
            adminStars.forEach((s, i) => s.classList.toggle('filled', i < currentAdminRating));
        });
        star.addEventListener('click', () => {
            currentAdminRating = 5 - index;
            ratingRadios[4 - index].checked = true;
            adminStars.forEach((s, i) => s.classList.toggle('filled', i <= index));
        });
    });
});
</script>
@endpush
@endsection
