<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SeoMetadata;
use App\Services\Seo\SeoMetadataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('categories.view');

$categories = Category::withCount('products')
            ->with(['children' => function ($query) {
                $query->withCount('products');
            }])
            ->whereNull('parent_id')
            ->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('categories.create');

        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('categories.create');

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'seo_meta' => 'nullable|array',
            'seo_meta.focus_keyword' => 'nullable|string|max:255',
            'seo_meta.secondary_keywords_text' => 'nullable|string|max:1000',
            'seo_meta.keywords' => 'nullable|string|max:1000',
            'seo_meta.canonical_url' => 'nullable|url|max:255',
            'seo_meta.robots_index' => 'nullable|boolean',
            'seo_meta.robots_follow' => 'nullable|boolean',
            'seo_meta.noindex_reason' => 'nullable|string|max:255',
            'seo_meta.og_title' => 'nullable|string|max:255',
            'seo_meta.og_description' => 'nullable|string|max:500',
            'seo_meta.og_image' => 'nullable|url|max:255',
            'seo_meta.og_image_alt' => 'nullable|string|max:255',
            'seo_meta.twitter_title' => 'nullable|string|max:255',
            'seo_meta.twitter_description' => 'nullable|string|max:500',
            'seo_meta.twitter_image' => 'nullable|url|max:255',
            'seo_meta.schema_type' => 'nullable|string|max:100',
            'seo_meta.schema_data' => 'nullable|string|max:10000',
            'seo_meta.sitemap_priority' => 'nullable|numeric|min:0.1|max:1',
            'seo_meta.sitemap_changefreq' => 'nullable|in:always,hourly,daily,weekly,monthly,yearly,never',
        ]);

        $data = $request->except('seo_meta');

        if (!$request->slug) {
            $data['slug'] = Str::slug($request->name);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        // Purge any trashed category with same slug to allow reuse after delete
        $slug = $data['slug'];
        $trashed = Category::withTrashed()->where('slug', $slug)->first();
        if ($trashed) {
            $trashed->forceDelete();
        }

        $category = Category::create($data);
        app(SeoMetadataService::class)->syncForModel($category, $request->input('seo_meta', []), [
            'title' => $category->meta_title,
            'description' => $category->meta_description,
            'schema_type' => 'CollectionPage',
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $this->authorize('categories.update');

        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        $seoMetadata = SeoMetadata::query()
            ->where('seoable_type', Category::class)
            ->where('seoable_id', $category->id)
            ->first();

        return view('admin.categories.edit', compact('category', 'categories', 'seoMetadata'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('categories.update');

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $category->id,
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'seo_meta' => 'nullable|array',
            'seo_meta.focus_keyword' => 'nullable|string|max:255',
            'seo_meta.secondary_keywords_text' => 'nullable|string|max:1000',
            'seo_meta.keywords' => 'nullable|string|max:1000',
            'seo_meta.canonical_url' => 'nullable|url|max:255',
            'seo_meta.robots_index' => 'nullable|boolean',
            'seo_meta.robots_follow' => 'nullable|boolean',
            'seo_meta.noindex_reason' => 'nullable|string|max:255',
            'seo_meta.og_title' => 'nullable|string|max:255',
            'seo_meta.og_description' => 'nullable|string|max:500',
            'seo_meta.og_image' => 'nullable|url|max:255',
            'seo_meta.og_image_alt' => 'nullable|string|max:255',
            'seo_meta.twitter_title' => 'nullable|string|max:255',
            'seo_meta.twitter_description' => 'nullable|string|max:500',
            'seo_meta.twitter_image' => 'nullable|url|max:255',
            'seo_meta.schema_type' => 'nullable|string|max:100',
            'seo_meta.schema_data' => 'nullable|string|max:10000',
            'seo_meta.sitemap_priority' => 'nullable|numeric|min:0.1|max:1',
            'seo_meta.sitemap_changefreq' => 'nullable|in:always,hourly,daily,weekly,monthly,yearly,never',
        ]);

        $data = $request->except('seo_meta');

        if (!$request->slug) {
            $data['slug'] = Str::slug($request->name);
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        // Purge any trashed category with same slug (exclude current) to allow reuse after delete
        $slug = $data['slug'];
        $trashed = Category::withTrashed()
            ->where('slug', $slug)
            ->where('id', '!=', $category->id)
            ->first();
        if ($trashed) {
            $trashed->forceDelete();
        }

        $category->update($data);
        app(SeoMetadataService::class)->syncForModel($category->fresh(), $request->input('seo_meta', []), [
            'title' => $category->meta_title,
            'description' => $category->meta_description,
            'schema_type' => 'CollectionPage',
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('categories.delete');

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category moved to Recycle Bin successfully.');
    }
}

