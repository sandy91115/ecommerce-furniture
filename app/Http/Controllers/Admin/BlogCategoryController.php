<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryStoreRequest;
use App\Http\Requests\Admin\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Storage;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('blogs.view');

        $categories = BlogCategory::withCount('blogs')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('blogs.create');

        return view('admin.blog-categories.create');
    }

    public function store(BlogCategoryStoreRequest $request)
    {
        $this->authorize('blogs.create');

        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog-categories', 'public');
        }

        BlogCategory::create($data);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(BlogCategory $blogCategory)
    {
        $this->authorize('blogs.view');

        return redirect()->route('admin.blog-categories.edit', $blogCategory);
    }

    public function edit(BlogCategory $blogCategory)
    {
        $this->authorize('blogs.update');

        return view('admin.blog-categories.edit', ['category' => $blogCategory]);
    }

    public function update(BlogCategoryUpdateRequest $request, BlogCategory $blogCategory)
    {
        $this->authorize('blogs.update');

        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            if ($blogCategory->image) {
                Storage::disk('public')->delete($blogCategory->image);
            }

            $data['image'] = $request->file('image')->store('blog-categories', 'public');
        }

        $blogCategory->update($data);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $this->authorize('blogs.delete');

        if ($blogCategory->image) {
            Storage::disk('public')->delete($blogCategory->image);
        }

        $blogCategory->delete();

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
