<?php

namespace App\View\Composers;

use App\Models\Review;
use Illuminate\View\View;

class ReviewComposer
{
    public function compose(View $view)
    {
        $testimonials = Review::where('status', 'approved')
            ->whereRaw("TRIM(COALESCE(comment, '')) <> ''")
            ->whereHas('product', fn ($query) => $query->where('status', 'active'))
            ->with(['user:id,name', 'product:id,name,slug,status'])
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $view->with('testimonials', $testimonials);
    }
}

