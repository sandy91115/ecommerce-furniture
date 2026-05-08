<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
<<<<<<< HEAD
use App\Models\User;
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
use App\Http\Requests\Admin\ReviewStoreRequest;
use App\Http\Requests\Admin\ReviewUpdateRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $this->authorize('reviews.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $reviews = Review::with(['user', 'product'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
<<<<<<< HEAD
        $this->authorize('reviews.create');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('admin.reviews.create');
    }

    public function store(ReviewStoreRequest $request)
    {
<<<<<<< HEAD
        $this->authorize('reviews.create');

        $data = $request->validated();
        $data['user_id'] = $data['user_id'] ?? auth()->id() ?? User::query()->value('id');

        Review::create($data);
=======
        Review::create($request->validated());
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return redirect()->route('admin.reviews.index')->with('success', 'Review created successfully.');
    }

    public function show(Review $review)
    {
<<<<<<< HEAD
        $this->authorize('reviews.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
<<<<<<< HEAD
        $this->authorize('reviews.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $review->load(['user', 'product']);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(ReviewUpdateRequest $request, Review $review)
    {
<<<<<<< HEAD
        $this->authorize('reviews.update');

        $data = $request->validated();
        $data['user_id'] = $data['user_id'] ?? $review->user_id ?? auth()->id() ?? User::query()->value('id');

        $review->update($data);
=======
        $review->update($request->validated());
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
<<<<<<< HEAD
        $this->authorize('reviews.delete');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }

    public function approve(Review $review)
    {
<<<<<<< HEAD
        $this->authorize('reviews.moderate');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $review->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Review approved.');
    }

    public function reject(Review $review)
    {
<<<<<<< HEAD
        $this->authorize('reviews.moderate');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $review->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Review rejected.');
    }
}

