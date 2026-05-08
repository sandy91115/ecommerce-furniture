<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogStoreRequest;
use App\Http\Requests\Admin\BlogUpdateRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\SeoMetadata;
use App\Services\BlogService;
use App\Services\Seo\SeoMetadataService;

class BlogController extends Controller
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index()
    {
        $this->authorize('blogs.view');

        $blogs = $this->blogService->all();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $this->authorize('blogs.create');

        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(BlogStoreRequest $request)
    {
        $this->authorize('blogs.create');

        $data = $request->validated();
        $data['tags'] = $data['tags'] ?? [];

        if (isset($data['status']) && $data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $seoMeta = $data['seo_meta'] ?? [];
        unset($data['seo_meta']);

        $blog = $this->blogService->create($data, $request->file('featured_image'));
        app(SeoMetadataService::class)->syncForModel($blog, $seoMeta, [
            'title' => $blog->title,
            'description' => $blog->excerpt,
            'schema_type' => 'Article',
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    public function show(Blog $blog)
    {
        $this->authorize('blogs.view');

        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $this->authorize('blogs.update');

        $seoMetadata = SeoMetadata::query()
            ->where('seoable_type', Blog::class)
            ->where('seoable_id', $blog->id)
            ->first();

        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.edit', compact('blog', 'seoMetadata', 'categories'));
    }

    public function update(BlogUpdateRequest $request, Blog $blog)
    {
        $this->authorize('blogs.update');

        $data = $request->validated();
        $data['tags'] = $data['tags'] ?? [];

        if (isset($data['status']) && $data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $seoMeta = $data['seo_meta'] ?? [];
        unset($data['seo_meta']);

        $blog = $this->blogService->update($blog, $data, $request->file('featured_image'));
        app(SeoMetadataService::class)->syncForModel($blog, $seoMeta, [
            'title' => $blog->title,
            'description' => $blog->excerpt,
            'schema_type' => 'Article',
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->authorize('blogs.delete');

        $this->blogService->delete($blog);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog moved to Recycle Bin successfully.');
    }
}
