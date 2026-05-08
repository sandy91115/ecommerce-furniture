<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\Seo\SeoManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogV2Controller extends Controller
{
    public function show($title = null)
    {
        $baseQuery = Blog::where('status', 'published')
            ->where('published_at', '<=', Carbon::now())
            ->with('categories');

        $blog = $title
            ? (clone $baseQuery)->where('slug', $title)->firstOrFail()
            : $baseQuery->latest('published_at')->firstOrFail();

        $categoryNames = $blog->categories->pluck('name')->values();

        // Map to frontend format
        $item = [
            'id' => $blog->id,
            'img' => $blog->image_url,
            'title' => $blog->title,
            'tag' => $categoryNames->first() ?? 'Blog',
            'date' => $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y'),
            'desc' => $blog->excerpt,
            'tags' => $categoryNames,
        ];

        $seo = app(SeoManager::class)->forBlog($blog);

        return view('blog-details-v2', compact('item', 'seo'));
    }
}
