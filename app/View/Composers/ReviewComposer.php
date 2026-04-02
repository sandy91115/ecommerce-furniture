<?php

namespace App\View\Composers;

use App\Models\Review;
use Illuminate\View\View;

class ReviewComposer
{
    public function compose(View $view)
    {
        $testimonials = Review::where('status', 'approved')
            ->with('user:id,name')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $view->with('testimonials', $testimonials);
    }
}

