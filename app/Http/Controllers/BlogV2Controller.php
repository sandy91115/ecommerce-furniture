<?php

namespace App\Http\Controllers;

use App\Models\Blog;
<<<<<<< HEAD
use App\Services\Seo\SeoManager;
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogV2Controller extends Controller
{
    public function show($title = null)
    {
        $baseQuery = Blog::where('status', 'published')
<<<<<<< HEAD
            ->where('published_at', '<=', Carbon::now())
            ->with('categories');
=======
            ->where('published_at', '<=', Carbon::now());
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        $blog = $title
            ? (clone $baseQuery)->where('slug', $title)->firstOrFail()
            : $baseQuery->latest('published_at')->firstOrFail();

<<<<<<< HEAD
        $categoryNames = $blog->categories->pluck('name')->values();

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        // Map to frontend format
        $item = [
            'id' => $blog->id,
            'img' => $blog->image_url,
            'title' => $blog->title,
<<<<<<< HEAD
            'tag' => $categoryNames->first() ?? 'Blog',
            'date' => $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y'),
            'desc' => $blog->excerpt,
            'tags' => $categoryNames,
        ];

        $seo = app(SeoManager::class)->forBlog($blog);

        return view('blog-details-v2', compact('item', 'seo'));
=======
            'tag' => $blog->tags ? $blog->tags[0] ?? 'Blog' : 'Blog',
            'date' => $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y'),
            'desc' => $blog->excerpt,
            'tags' => $blog->tags,
        ];

        return view('blog-details-v2', compact('item'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }
}
