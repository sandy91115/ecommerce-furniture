<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReviewNotification;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
<<<<<<< HEAD
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => substr($request->comment, 0, 100),
=======
            'user_id' => Auth::id() ?? null, // Optional guest review
            'rating' => $request->rating,
            'title' => substr($request->comment, 0, 100), // Short title
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

<<<<<<< HEAD
=======
        // Optional: Create guest user if no auth
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        if (!$review->user_id && User::where('email', $request->email)->exists()) {
            $user = User::where('email', $request->email)->first();
            $review->update(['user_id' => $user->id]);
        }

        // Notify admin
        // Mail::to(config('mail.admin'))->send(new NewReviewNotification($review));

        return response()->json(['success' => true, 'message' => 'Review submitted! It will be visible after approval.']);
    }

    public function frontendReviews($productId)
    {
        $product = Product::findOrFail($productId);
        $approvedReviews = Review::where('product_id', $productId)
            ->where('status', 'approved')
            ->with('user:id,name')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($r) => [
<<<<<<< HEAD
                'name' => $r->reviewer_name ?: ($r->user->name ?? 'Anonymous'),
=======
                'name' => $r->user->name ?? 'Anonymous',
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                'rating' => $r->rating,
                'title' => $r->title ?? $r->comment ?? '',
                'comment' => $r->comment,
                'created_at' => $r->created_at,
                'review' => $r,
                'status' => $r->status,
            ]);

        return response()->json([
            'reviews' => $approvedReviews,
            'avgRating' => $approvedReviews->avg('rating'),
            'totalReviews' => Review::where('product_id', $productId)->where('status', 'approved')->count(),
        ]);
    }
}

