<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Http\Requests\Admin\ReviewStoreRequest;
use App\Http\Requests\Admin\ReviewUpdateRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('reviews.view');

        $reviews = Review::with(['user', 'product'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $this->authorize('reviews.create');

        return view('admin.reviews.create');
    }

    public function store(ReviewStoreRequest $request)
    {
        $this->authorize('reviews.create');

        $data = $request->validated();
        $data['user_id'] = $data['user_id'] ?? auth()->id() ?? User::query()->value('id');

        Review::create($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Review created successfully.');
    }

    public function show(Review $review)
    {
        $this->authorize('reviews.view');

        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $this->authorize('reviews.update');

        $review->load(['user', 'product']);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(ReviewUpdateRequest $request, Review $review)
    {
        $this->authorize('reviews.update');

        $data = $request->validated();
        $data['user_id'] = $data['user_id'] ?? $review->user_id ?? auth()->id() ?? User::query()->value('id');

        $review->update($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $this->authorize('reviews.delete');

        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }

    public function approve(Review $review)
    {
        $this->authorize('reviews.moderate');

        $review->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Review approved.');
    }

    public function reject(Review $review)
    {
        $this->authorize('reviews.moderate');

        $review->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Review rejected.');
    }
}

