<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\SeoMetadata;
use App\Models\Setting;
use Illuminate\Http\Response;

class SeoPublicController extends Controller
{
    public function robots(): Response
    {
        $adminPath = trim(env('ADMIN_PATH', 'panel'), '/') ?: 'panel';
        $extraDisallow = collect(preg_split('/[\r\n,]+/', (string) Setting::get(Setting::SEO_ROBOTS_EXTRA_DISALLOW, '')) ?: [])
            ->map(fn ($path) => trim($path))
            ->filter()
            ->map(fn ($path) => str_starts_with($path, '/') ? $path : '/' . $path)
            ->values();

        $disallow = collect([
            '/' . $adminPath . '/',
            '/admin/',
            '/cart',
            '/checkout',
            '/dashboard',
            '/my-account',
            '/my-profile',
            '/edit-account',
            '/wishlist',
            '/search',
        ])->merge($extraDisallow)->unique()->values();

        $lines = collect(['User-agent: *'])
            ->merge($disallow->map(fn ($path) => 'Disallow: ' . $path))
            ->push('Sitemap: ' . url('/sitemap.xml'))
            ->push('');

        return response($lines->implode("\n"), 200)->header('Content-Type', 'text/plain');
    }

    public function sitemapIndex(): Response
    {
        $productLastmod = Product::query()->where('status', 'active')->latest('updated_at')->first()?->updated_at?->toDateString() ?: now()->toDateString();
        $categoryLastmod = Category::query()->where('status', 'active')->latest('updated_at')->first()?->updated_at?->toDateString() ?: now()->toDateString();

        $sitemaps = [
            ['loc' => url('/sitemap-pages.xml'), 'lastmod' => now()->toDateString()],
            ['loc' => url('/sitemap-products.xml'), 'lastmod' => $productLastmod],
            ['loc' => url('/sitemap-categories.xml'), 'lastmod' => $categoryLastmod],
            ['loc' => url('/sitemap-images.xml'), 'lastmod' => $productLastmod],
        ];

        return response()
            ->view('seo.sitemap-index', ['sitemaps' => $sitemaps])
            ->header('Content-Type', 'application/xml');
    }

    public function products(): Response
    {
        $priority = Setting::get(Setting::SEO_SITEMAP_PRODUCTS_PRIORITY, '0.8');
        $urls = Product::query()
            ->with('seoMetadata')
            ->where('status', 'active')
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->where('robots_index', false))
            ->latest('updated_at')
            ->get(['id', 'slug', 'updated_at'])
            ->map(fn ($product) => [
                'loc' => $product->seoMetadata?->canonical_url ?: route('product-details', $product->slug),
                'lastmod' => optional($product->updated_at)->toDateString(),
                'priority' => $product->seoMetadata?->sitemap_priority ?: $priority,
                'changefreq' => $product->seoMetadata?->sitemap_changefreq ?: 'weekly',
            ]);

        return $this->sitemapResponse($urls);
    }

    public function categories(): Response
    {
        $priority = Setting::get(Setting::SEO_SITEMAP_CATEGORIES_PRIORITY, '0.7');
        $urls = Category::query()
            ->with('seoMetadata')
            ->where('status', 'active')
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->where('robots_index', false))
            ->latest('updated_at')
            ->get(['id', 'slug', 'updated_at'])
            ->map(fn ($category) => [
                'loc' => $category->seoMetadata?->canonical_url ?: route('shop.category', ['category' => $category->slug]),
                'lastmod' => optional($category->updated_at)->toDateString(),
                'priority' => $category->seoMetadata?->sitemap_priority ?: $priority,
                'changefreq' => $category->seoMetadata?->sitemap_changefreq ?: 'weekly',
            ]);

        return $this->sitemapResponse($urls);
    }

    public function pages(): Response
    {
        $priority = Setting::get(Setting::SEO_SITEMAP_PAGES_PRIORITY, '0.6');
        $staticPages = collect([
            ['key' => 'home', 'loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['key' => 'shop', 'loc' => route('shop'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['key' => 'blog', 'loc' => route('blog.index'), 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['key' => 'about', 'loc' => url('/about'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['key' => 'contact', 'loc' => url('/contact'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['key' => 'faq', 'loc' => url('/faq'), 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['key' => 'terms-and-conditions', 'loc' => url('/terms-and-conditions'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['key' => 'return-policy', 'loc' => url('/return-policy'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['key' => 'pricing', 'loc' => url('/pricing'), 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['key' => 'team', 'loc' => url('/team'), 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['key' => 'our-clients', 'loc' => url('/our-clients'), 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['key' => 'store.index', 'loc' => route('store.index'), 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['key' => 'quotation-products.index', 'loc' => route('quotation-products.index'), 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['key' => 'portfolio-v1', 'loc' => url('/portfolio-v1'), 'priority' => '0.4', 'changefreq' => 'monthly'],
        ])->map(function ($page) {
            $metadata = SeoMetadata::query()->where('page_key', $page['key'])->first();

            if ($metadata && ! $metadata->robots_index) {
                return null;
            }

            return [
                'loc' => $metadata?->canonical_url ?: $page['loc'],
                'lastmod' => optional($metadata?->updated_at)->toDateString() ?: now()->toDateString(),
                'priority' => $metadata?->sitemap_priority ?: $page['priority'],
                'changefreq' => $metadata?->sitemap_changefreq ?: $page['changefreq'],
            ];
        })->filter()->values();

        $cmsPages = CmsPage::query()
            ->with('seoMetadata')
            ->active()
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->where('robots_index', false))
            ->latest('updated_at')
            ->get(['id', 'slug', 'updated_at'])
            ->map(fn ($page) => [
                'loc' => $page->seoMetadata?->canonical_url ?: url($page->slug),
                'lastmod' => optional($page->updated_at)->toDateString(),
                'priority' => $page->seoMetadata?->sitemap_priority ?: $priority,
                'changefreq' => $page->seoMetadata?->sitemap_changefreq ?: 'monthly',
            ]);

        $blogs = Blog::query()
            ->with('seoMetadata')
            ->published()
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->where('robots_index', false))
            ->get(['id', 'slug', 'updated_at'])
            ->map(fn ($blog) => [
                'loc' => $blog->seoMetadata?->canonical_url ?: route('blog.show', $blog->slug),
                'lastmod' => optional($blog->updated_at)->toDateString(),
                'priority' => $blog->seoMetadata?->sitemap_priority ?: $priority,
                'changefreq' => $blog->seoMetadata?->sitemap_changefreq ?: 'monthly',
            ]);

        return $this->sitemapResponse($staticPages->merge($cmsPages)->merge($blogs)->values());
    }

    public function images(): Response
    {
        $products = Product::query()
            ->with(['images', 'seoMetadata'])
            ->where('status', 'active')
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->where('robots_index', false))
            ->whereHas('images')
            ->latest('updated_at')
            ->get();

        $urls = $products->map(function (Product $product) {
            return [
                'loc' => $product->seoMetadata?->canonical_url ?: route('product-details', $product->slug),
                'lastmod' => optional($product->updated_at)->toDateString(),
                'images' => $product->images
                    ->sortBy([['featured', 'desc'], ['sort_order', 'asc'], ['id', 'asc']])
                    ->map(fn ($image) => [
                        'loc' => $image->path ? asset('storage/' . ltrim($image->path, '/')) : null,
                        'title' => $image->title ?: $image->alt ?: $product->name,
                        'caption' => $image->caption ?: $image->description ?: $product->short_description,
                    ])
                    ->filter(fn ($image) => filled($image['loc']))
                    ->values()
                    ->all(),
            ];
        })->filter(fn ($url) => $url['images'] !== [])->values();

        return response()
            ->view('seo.image-sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    private function sitemapResponse($urls): Response
    {
        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
