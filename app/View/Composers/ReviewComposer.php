<?php

namespace App\View\Composers;

use App\Models\Review;
use Illuminate\View\View;

class ReviewComposer
{
    public function compose(View $view)
    {
        $testimonials = Review::where('status', 'approved')
<<<<<<< HEAD
            ->whereRaw("TRIM(COALESCE(comment, '')) <> ''")
            ->whereHas('product', fn ($query) => $query->where('status', 'active'))
            ->with(['user:id,name', 'product:id,name,slug,status'])
            ->inRandomOrder()
            ->limit(5)
=======
            ->with('user:id,name')
            ->inRandomOrder()
            ->limit(3)
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            ->get();

        $view->with('testimonials', $testimonials);
    }
}

