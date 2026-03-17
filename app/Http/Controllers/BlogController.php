<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function index()
    {
        $featuredBlogs = Blog::published()->limit(4)->get();
        $latestBlogs = Blog::published()->limit(10)->get();
        return view('blog', compact('featuredBlogs', 'latestBlogs'));
    }
    public function show($slug = null)
    {
        $blog = $slug
            ? Blog::published()->where('slug', $slug)->firstOrFail()
            : Blog::published()->firstOrFail();
        
        $prevBlog = Blog::published()->where('id', '<', $blog->id)->orderBy('id', 'desc')->first();
        $nextBlog = Blog::published()->where('id', '>', $blog->id)->orderBy('id')->first();
        $recentBlogs = Blog::published()->where('id', '!=', $blog->id)->limit(4)->get();
        
        $relatedQuery = Blog::published()->where('id', '!=', $blog->id);
        if ($blog->tags) {
            $relatedQuery->where(function($q) use ($blog) {
                foreach ($blog->tags as $tag) {
                    $q->orWhereJsonContains('tags', $tag);
                }
            });
        }
        $relatedBlogs = $relatedQuery->limit(5)->get();
        
        $categories = Blog::published()->pluck('tags')->flatten()->unique()->values();
        $tags = $blog->tags ?? [];

        return view('blog-details-v1', compact(
            'blog', 'prevBlog', 'nextBlog', 
            'recentBlogs', 'relatedBlogs', 
            'categories', 'tags'
        ));
    }
}
