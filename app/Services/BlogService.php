<?php

namespace App\Services;

use App\Models\Blog;
<<<<<<< HEAD
=======
use Illuminate\Support\Str;
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids'], $data['tags']);

        if ($image) {
            $data['featured_image'] = $image->store('blogs', 'public');
            $this->mirrorToPublicStorage($data['featured_image']);
        }

        $blog = Blog::create($data);
        $blog->categories()->sync($categoryIds);

        return $blog;
=======
        if ($image) {
            $data['featured_image'] = $image->store('blogs', 'public');
        }

        return Blog::create($data);
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function update(Blog $blog, array $data, ?UploadedFile $image = null): Blog
    {
<<<<<<< HEAD
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids'], $data['tags']);

        if ($image) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
                $this->deletePublicMirror($blog->featured_image);
            }
            $data['featured_image'] = $image->store('blogs', 'public');
            $this->mirrorToPublicStorage($data['featured_image']);
        }

        $blog->update($data);
        $blog->categories()->sync($categoryIds);
=======
        if ($image) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $image->store('blogs', 'public');
        }

        $blog->update($data);
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return $blog->fresh();
    }

    public function delete(Blog $blog): bool
    {
        // Don't delete image on soft delete, as it might be restored
        return $blog->delete();
    }

<<<<<<< HEAD
    private function mirrorToPublicStorage(string $path): void
    {
        $publicStoragePath = public_path('storage');

        if (is_link($publicStoragePath)) {
            return;
        }

        $source = Storage::disk('public')->path($path);
        $destination = $publicStoragePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $destinationDirectory = dirname($destination);

        if (! is_dir($destinationDirectory)) {
            mkdir($destinationDirectory, 0755, true);
        }

        if (is_file($source)) {
            copy($source, $destination);
        }
    }

    private function deletePublicMirror(string $path): void
    {
        $publicStoragePath = public_path('storage');

        if (is_link($publicStoragePath)) {
            return;
        }

        $file = $publicStoragePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

        if (is_file($file)) {
            unlink($file);
        }
    }

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    public function lowStock()
    {
        // Not applicable for blogs
        return collect();
    }
}
<<<<<<< HEAD
=======

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
