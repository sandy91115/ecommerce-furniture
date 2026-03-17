<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogV2Controller extends Controller
{
    public function show($title = null)
    {
        $baseQuery = Blog::where('status', 'published')
            ->where('published_at', '<=', Carbon::now());

        $blog = $title
            ? (clone $baseQuery)->where('slug', $title)->firstOrFail()
            : $baseQuery->latest('published_at')->firstOrFail();

        // Map to frontend format
        $item = [
            'id' => $blog->id,
            'img' => $blog->image_url,
            'title' => $blog->title,
            'tag' => $blog->tags ? $blog->tags[0] ?? 'Blog' : 'Blog',
            'date' => $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y'),
            'desc' => $blog->excerpt,
            'tags' => $blog->tags,
        ];

        return view('blog-details-v2', compact('item'));
    }
}
