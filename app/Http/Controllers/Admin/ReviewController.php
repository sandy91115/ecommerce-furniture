<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Requests\Admin\ReviewStoreRequest;
use App\Http\Requests\Admin\ReviewUpdateRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'product'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.reviews.create');
    }

    public function store(ReviewStoreRequest $request)
    {
        Review::create($request->validated());

        return redirect()->route('admin.reviews.index')->with('success', 'Review created successfully.');
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(ReviewUpdateRequest $request, Review $review)
    {
        $review->update($request->validated());

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Review approved.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Review rejected.');
    }
}

