<?php

use App\Models\Blog;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seo_metadata')) {
            return;
        }

        Product::query()->select(['id', 'name', 'seo_title', 'seo_description', 'short_description', 'updated_at'])->chunkById(100, function ($products) {
            foreach ($products as $product) {
                $this->upsertModelSeo(Product::class, $product->id, [
                    'title' => $product->seo_title ?: $product->name,
                    'description' => $product->seo_description ?: $product->short_description,
                    'schema_type' => 'Product',
                    'updated_at' => $product->updated_at ?: now(),
                ]);
            }
        });

        Category::query()->select(['id', 'name', 'meta_title', 'meta_description', 'updated_at'])->chunkById(100, function ($categories) {
            foreach ($categories as $category) {
                $this->upsertModelSeo(Category::class, $category->id, [
                    'title' => $category->meta_title ?: $category->name,
                    'description' => $category->meta_description,
                    'schema_type' => 'CollectionPage',
                    'updated_at' => $category->updated_at ?: now(),
                ]);
            }
        });

        CmsPage::query()->select(['id', 'title', 'meta_title', 'meta_description', 'content', 'updated_at'])->chunkById(100, function ($pages) {
            foreach ($pages as $page) {
                $content = is_array($page->content) ? json_encode($page->content) : (string) $page->content;
                $this->upsertModelSeo(CmsPage::class, $page->id, [
                    'title' => $page->meta_title ?: $page->title,
                    'description' => $page->meta_description ?: Str::limit(strip_tags($content), 160, ''),
                    'schema_type' => 'WebPage',
                    'updated_at' => $page->updated_at ?: now(),
                ]);
            }
        });

        Blog::query()->select(['id', 'title', 'excerpt', 'content', 'updated_at'])->chunkById(100, function ($blogs) {
            foreach ($blogs as $blog) {
                $this->upsertModelSeo(Blog::class, $blog->id, [
                    'title' => $blog->title,
                    'description' => $blog->excerpt ?: Str::limit(strip_tags($blog->content), 160, ''),
                    'schema_type' => 'Article',
                    'updated_at' => $blog->updated_at ?: now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        // Keep generated SEO metadata; it may have been edited after migration.
    }

    private function upsertModelSeo(string $type, int $id, array $payload): void
    {
        if (DB::table('seo_metadata')->where('seoable_type', $type)->where('seoable_id', $id)->exists()) {
            return;
        }

        DB::table('seo_metadata')->updateOrInsert(
            ['seoable_type' => $type, 'seoable_id' => $id],
            array_merge([
                'robots_index' => true,
                'robots_follow' => true,
                'created_at' => now(),
            ], $payload)
        );
    }
};
