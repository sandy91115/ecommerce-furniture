<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Services\Seo\SeoManager;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()->with('categories');
        
        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        $featuredBlogs = Blog::published()->with('categories')->limit(4)->get();
        $blogs = $query->paginate(12);
        $categories = BlogCategory::withCount(['blogs' => fn($q) => $q->published()])
            ->orderBy('name')
            ->get();
        $publishedBlogsCount = Blog::published()->count();
        $selectedCategory = $request->filled('category')
            ? $categories->firstWhere('slug', $request->category)
            : null;
        
        $seo = app(SeoManager::class)->forBlogIndex();

        return view('blog', compact('featuredBlogs', 'blogs', 'categories', 'publishedBlogsCount', 'selectedCategory', 'request', 'seo'));
    }
    public function show($slug = null)
    {
        $blogQuery = Blog::published()->with('categories');

        $blog = $slug
            ? (clone $blogQuery)->where('slug', $slug)->firstOrFail()
            : $blogQuery->firstOrFail();
        
        $blog->load(['user', 'approvedComments.user.children.user']);
        
        $prevBlog = Blog::published()->with('categories')->where('id', '<', $blog->id)->orderBy('id', 'desc')->first();
        $nextBlog = Blog::published()->with('categories')->where('id', '>', $blog->id)->orderBy('id')->first();
        $recentBlogs = Blog::published()->with('categories')->where('id', '!=', $blog->id)->limit(4)->get();
        
        $relatedQuery = Blog::published()->with('categories')->where('id', '!=', $blog->id);
        $categoryIds = $blog->categories->pluck('id');

        if ($categoryIds->isNotEmpty()) {
            $relatedQuery->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('blog_categories.id', $categoryIds);
            });
        }

        $relatedBlogs = $relatedQuery->limit(5)->get();
        
        $categories = BlogCategory::whereHas('blogs', fn ($query) => $query->published())
            ->orderBy('name')
            ->pluck('name');
        $tags = $blog->categories->pluck('name')->values()->all();
        $seo = app(SeoManager::class)->forBlog($blog);

        return view('blog-details-v1', compact(
            'blog', 'prevBlog', 'nextBlog', 
            'recentBlogs', 'relatedBlogs', 
            'categories', 'tags', 'seo'
        ));
    }
}
