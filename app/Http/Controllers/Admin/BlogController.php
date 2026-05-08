<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogStoreRequest;
use App\Http\Requests\Admin\BlogUpdateRequest;
use App\Models\Blog;
<<<<<<< HEAD
use App\Models\BlogCategory;
use App\Models\SeoMetadata;
use App\Services\BlogService;
use App\Services\Seo\SeoMetadataService;
=======
use App\Services\BlogService;
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

class BlogController extends Controller
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index()
    {
<<<<<<< HEAD
        $this->authorize('blogs.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $blogs = $this->blogService->all();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
<<<<<<< HEAD
        $this->authorize('blogs.create');

        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.create', compact('categories'));
=======
        return view('admin.blogs.create');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function store(BlogStoreRequest $request)
    {
<<<<<<< HEAD
        $this->authorize('blogs.create');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $data = $request->validated();
        $data['tags'] = $data['tags'] ?? [];

        if (isset($data['status']) && $data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

<<<<<<< HEAD
        $seoMeta = $data['seo_meta'] ?? [];
        unset($data['seo_meta']);

        $blog = $this->blogService->create($data, $request->file('featured_image'));
        app(SeoMetadataService::class)->syncForModel($blog, $seoMeta, [
            'title' => $blog->title,
            'description' => $blog->excerpt,
            'schema_type' => 'Article',
        ]);
=======
        $this->blogService->create($data, $request->file('featured_image'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    public function show(Blog $blog)
    {
<<<<<<< HEAD
        $this->authorize('blogs.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
<<<<<<< HEAD
        $this->authorize('blogs.update');

        $seoMetadata = SeoMetadata::query()
            ->where('seoable_type', Blog::class)
            ->where('seoable_id', $blog->id)
            ->first();

        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.edit', compact('blog', 'seoMetadata', 'categories'));
=======
        return view('admin.blogs.edit', compact('blog'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function update(BlogUpdateRequest $request, Blog $blog)
    {
<<<<<<< HEAD
        $this->authorize('blogs.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $data = $request->validated();
        $data['tags'] = $data['tags'] ?? [];

        if (isset($data['status']) && $data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

<<<<<<< HEAD
        $seoMeta = $data['seo_meta'] ?? [];
        unset($data['seo_meta']);

        $blog = $this->blogService->update($blog, $data, $request->file('featured_image'));
        app(SeoMetadataService::class)->syncForModel($blog, $seoMeta, [
            'title' => $blog->title,
            'description' => $blog->excerpt,
            'schema_type' => 'Article',
        ]);
=======
        $this->blogService->update($blog, $data, $request->file('featured_image'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
<<<<<<< HEAD
        $this->authorize('blogs.delete');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $this->blogService->delete($blog);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog moved to Recycle Bin successfully.');
    }
}
