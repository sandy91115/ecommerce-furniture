<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BlogService
{
    public function all($perPage = 10)
    {
        return Blog::latest()->paginate($perPage);
    }

    public function published($limit = null)
    {
        $query = Blog::published();
        if ($limit) {
            $query->limit($limit);
        }
        return $query->get();
    }

    public function featured()
    {
        return $this->published(4);
    }

    public function create(array $data, ?UploadedFile $image = null): Blog
    {
        if ($image) {
            $data['featured_image'] = $image->store('blogs', 'public');
        }

        return Blog::create($data);
    }

    public function update(Blog $blog, array $data, ?UploadedFile $image = null): Blog
    {
        if ($image) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $image->store('blogs', 'public');
        }

        $blog->update($data);

        return $blog->fresh();
    }

    public function delete(Blog $blog): bool
    {
        // Don't delete image on soft delete, as it might be restored
        return $blog->delete();
    }

    public function lowStock()
    {
        // Not applicable for blogs
        return collect();
    }
}

