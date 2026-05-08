<?php

namespace App\Services\Seo;

use App\Models\Blog;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\Review;
use App\Models\SeoMetadata;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SeoManager
{
    public function forCurrentPage(?string $title = null, ?string $description = null, ?string $pageKey = null): SeoData
    {
        $pageKey ??= request()->route()?->getName() ?: trim(request()->path(), '/') ?: 'home';
        $metadata = SeoMetadata::query()->where('page_key', $pageKey)->first();
        $canonical = $metadata?->canonical_url ?: $this->canonicalForCurrentRequest();

        return $this->build(
            title: $metadata?->title ?: $title ?: $this->siteName(),
            description: $metadata?->description ?: $description ?: $this->defaultDescription(),
            canonical: $canonical,
            metadata: $metadata,
            schemas: [
                ...$this->baseSchemas(),
                $this->webPageSchema($metadata?->title ?: $title ?: $this->siteName(), $canonical, $metadata?->description ?: $description),
            ]
        );
    }

    public function forShop(?Category $category = null): SeoData
    {
        if ($category) {
            return $this->forCategory($category);
        }

        $metadata = SeoMetadata::query()->where('page_key', 'shop')->first();
        $canonical = $metadata?->canonical_url ?: route('shop');
        $title = $metadata?->title ?: 'Shop Furniture Online';
        $description = $metadata?->description ?: 'Explore premium furniture, home decor, custom tables, seating, storage, and handmade designs.';

        return $this->build(
            title: $title,
            description: $description,
            canonical: $canonical,
            metadata: $metadata,
            schemas: [
                ...$this->baseSchemas(),
                $this->collectionPageSchema($title, $description, $canonical),
                $this->breadcrumbSchema([
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => 'Shop', 'url' => $canonical],
                ]),
            ]
        );
    }

    public function forProduct(Product $product): SeoData
    {
        $product->loadMissing(['category.parent', 'vendor', 'images', 'reviews.user', 'material', 'color']);
        $metadata = $this->metadataFor($product);
        $canonical = $metadata?->canonical_url ?: route('product-details', $product->slug);
        $description = $metadata?->description
            ?: $product->seo_description
            ?: $product->short_description
            ?: $product->description
            ?: $product->name;
        $title = $metadata?->title ?: $product->seo_title ?: $product->name;
        $image = $metadata?->og_image ?: $this->productImage($product);
        $imageAlt = $metadata?->og_image_alt ?: $this->productImageAlt($product);

        return $this->build(
            title: $title,
            description: $description,
            canonical: $canonical,
            metadata: $metadata,
            type: 'product',
            image: $image,
            imageAlt: $imageAlt,
            schemas: [
                $this->productSchema($product, $canonical),
                $this->breadcrumbSchema($this->productBreadcrumbs($product, $canonical)),
                $this->faqSchema($product->faqs ?: []),
                ...$this->imageObjectSchemas($product),
            ]
        );
    }

    public function forCategory(Category $category): SeoData
    {
        $category->loadMissing('parent');
        $metadata = $this->metadataFor($category);
        $canonical = $metadata?->canonical_url ?: route('shop.category', ['category' => $category->slug]);
        $title = $metadata?->title ?: $category->meta_title ?: $category->name;
        $description = $metadata?->description ?: $category->meta_description ?: "Explore {$category->name} furniture and home decor products.";
        $image = $metadata?->og_image ?: ($category->image ? asset('storage/' . ltrim($category->image, '/')) : null);

        return $this->build(
            title: $title,
            description: $description,
            canonical: $canonical,
            metadata: $metadata,
            image: $image,
            schemas: [
                ...$this->baseSchemas(),
                $this->collectionPageSchema($title, $description, $canonical),
                $this->breadcrumbSchema($this->categoryBreadcrumbs($category, $canonical)),
            ]
        );
    }

    public function forBlogIndex(): SeoData
    {
        $metadata = SeoMetadata::query()->where('page_key', 'blog')->first();
        $canonical = $metadata?->canonical_url ?: route('blog.index');
        $title = $metadata?->title ?: 'Furniture Ideas, Buying Guides and Design Inspiration';
        $description = $metadata?->description ?: 'Read furniture buying guides, styling ideas, care tips, and inspiration for modern homes.';

        return $this->build(
            title: $title,
            description: $description,
            canonical: $canonical,
            metadata: $metadata,
            schemas: [
                ...$this->baseSchemas(),
                $this->collectionPageSchema($title, $description, $canonical),
                $this->breadcrumbSchema([
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => 'Blog', 'url' => $canonical],
                ]),
            ]
        );
    }

    public function forBlog(Blog $blog): SeoData
    {
        $metadata = $this->metadataFor($blog);
        $canonical = $metadata?->canonical_url ?: route('blog.show', $blog->slug);
        $description = $metadata?->description ?: $blog->excerpt ?: Str::limit(strip_tags($blog->content), 160, '');
        $title = $metadata?->title ?: $blog->title;
        $image = $metadata?->og_image ?: ($blog->featured_image ? asset('storage/' . ltrim($blog->featured_image, '/')) : null);

        return $this->build(
            title: $title,
            description: $description,
            canonical: $canonical,
            metadata: $metadata,
            type: 'article',
            image: $image,
            imageAlt: $metadata?->og_image_alt ?: $blog->title,
            schemas: [
                ...$this->baseSchemas(),
                $this->articleSchema($blog, $canonical, $description, $image),
                $this->breadcrumbSchema([
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => 'Blog', 'url' => route('blog.index')],
                    ['name' => $blog->title, 'url' => $canonical],
                ]),
            ]
        );
    }

    public function forCmsPage(CmsPage $page): SeoData
    {
        $metadata = $this->metadataFor($page);
        $canonical = $metadata?->canonical_url ?: url($page->slug);
        $content = is_array($page->content) ? json_encode($page->content) : (string) $page->content;
        $title = $metadata?->title ?: $page->meta_title ?: $page->title;
        $description = $metadata?->description ?: $page->meta_description ?: Str::limit(strip_tags($content), 160, '');

        return $this->build(
            title: $title,
            description: $description,
            canonical: $canonical,
            metadata: $metadata,
            schemas: [
                ...$this->baseSchemas(),
                $this->webPageSchema($page->title, $canonical, $description),
                $this->breadcrumbSchema([
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => $page->title, 'url' => $canonical],
                ]),
            ]
        );
    }

    public function score(?string $title, ?string $description, ?string $focusKeyword = null, bool $hasImage = false, bool $hasSchema = false, bool $hasCanonical = true): int
    {
        $score = 0;
        $titleLength = mb_strlen((string) $title);
        $descriptionLength = mb_strlen((string) $description);
        $haystack = Str::lower((string) $title . ' ' . (string) $description);
        $keyword = Str::lower((string) $focusKeyword);

        if ($titleLength >= 45 && $titleLength <= 60) {
            $score += 25;
        } elseif ($titleLength >= 30 && $titleLength <= 70) {
            $score += 17;
        } elseif ($titleLength > 0) {
            $score += 8;
        }

        if ($descriptionLength >= 120 && $descriptionLength <= 160) {
            $score += 25;
        } elseif ($descriptionLength >= 90 && $descriptionLength <= 180) {
            $score += 17;
        } elseif ($descriptionLength > 0) {
            $score += 8;
        }

        if ($keyword !== '' && Str::contains($haystack, $keyword)) {
            $score += 20;
        } elseif ($keyword !== '') {
            $score += 8;
        }

        if ($hasImage) {
            $score += 10;
        }

        if ($hasCanonical) {
            $score += 10;
        }

        if ($hasSchema) {
            $score += 10;
        }

        return min(100, $score);
    }

    private function build(string $title, string $description, string $canonical, ?SeoMetadata $metadata = null, string $type = 'website', ?string $image = null, ?string $imageAlt = null, array $schemas = []): SeoData
    {
        $cleanTitle = $this->formatTitle($title);
        $cleanDescription = $this->cleanDescription($description);
        $robotsIndex = $metadata?->robots_index ?? Setting::bool(Setting::SEO_DEFAULT_ROBOTS_INDEX, true);
        $robotsFollow = $metadata?->robots_follow ?? Setting::bool(Setting::SEO_DEFAULT_ROBOTS_FOLLOW, true);

        if ($this->currentRequestShouldNoIndex()) {
            $robotsIndex = false;
        }

        $robots = ($robotsIndex ? 'index' : 'noindex') . ',' . ($robotsFollow ? 'follow' : 'nofollow');
        $image = $image ?: $this->defaultImage();
        $twitterImage = $metadata?->twitter_image ?: $image;

        $customSchema = $metadata?->schema_data ?: null;
        if (is_array($customSchema) && $customSchema !== []) {
            $schemas[] = $customSchema;
        }

        $schemas = collect($schemas)->filter(fn ($schema) => is_array($schema) && $schema !== [])->values()->all();

        return new SeoData(
            title: $cleanTitle,
            description: $cleanDescription,
            canonical: $canonical,
            keywords: $metadata?->keywords,
            robots: $robots,
            type: $type,
            image: $image,
            ogTitle: $metadata?->og_title ?: $cleanTitle,
            ogDescription: $metadata?->og_description ?: $cleanDescription,
            twitterTitle: $metadata?->twitter_title ?: $metadata?->og_title ?: $cleanTitle,
            twitterDescription: $metadata?->twitter_description ?: $metadata?->og_description ?: $cleanDescription,
            twitterImage: $twitterImage,
            imageAlt: $imageAlt ?: $metadata?->og_image_alt,
            siteName: $this->siteName(),
            schemas: $schemas,
        );
    }

    private function metadataFor(Model $model): ?SeoMetadata
    {
        if ($model->relationLoaded('seoMetadata')) {
            return $model->seoMetadata;
        }

        return SeoMetadata::query()
            ->where('seoable_type', $model::class)
            ->where('seoable_id', $model->getKey())
            ->first();
    }

    private function productSchema(Product $product, string $canonical): array
    {
        $price = (float) ($product->sale_price ?: $product->price);
        $images = $this->productImages($product)->pluck('url')->values();
        $reviews = $product->reviews->where('status', 'approved')->take(5)->map(function (Review $review) {
            return [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => $review->reviewer_name ?: $review->user?->name ?: 'Customer'],
                'reviewRating' => ['@type' => 'Rating', 'ratingValue' => (string) $review->rating, 'bestRating' => '5', 'worstRating' => '1'],
                'reviewBody' => $review->comment ?: $review->title,
                'datePublished' => optional($review->created_at)->toDateString(),
            ];
        })->values()->all();

        $properties = collect([
            $product->material?->name ? ['@type' => 'PropertyValue', 'name' => 'Material', 'value' => $product->material->name] : null,
            $product->color?->name ? ['@type' => 'PropertyValue', 'name' => 'Color', 'value' => $product->color->name] : null,
            $product->dimensions ? ['@type' => 'PropertyValue', 'name' => 'Dimensions', 'value' => is_array($product->dimensions) ? implode(' x ', array_filter($product->dimensions)) : (string) $product->dimensions] : null,
        ])->filter()->values()->all();

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'url' => $canonical,
            'image' => $images->isNotEmpty() ? $images->all() : [$this->defaultImage()],
            'description' => Str::limit(strip_tags($product->seo_description ?: $product->short_description ?: $product->description ?: $product->name), 500, ''),
            'sku' => $product->sku,
            'brand' => ['@type' => 'Brand', 'name' => $product->vendor?->store_name ?: $this->organizationName()],
            'category' => $product->category?->name,
            'additionalProperty' => $properties ?: null,
            'offers' => [
                '@type' => 'Offer',
                'url' => $canonical,
                'priceCurrency' => strtoupper((string) Setting::get(Setting::CURRENCY, 'INR')),
                'price' => number_format($price, 2, '.', ''),
                'availability' => $product->product_type === 'quotation' || (int) $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller' => ['@type' => 'Organization', 'name' => $this->organizationName()],
            ],
            'aggregateRating' => filled($product->product_rating) ? [
                '@type' => 'AggregateRating',
                'ratingValue' => number_format((float) $product->product_rating, 1, '.', ''),
                'reviewCount' => max(1, (int) ($product->product_rating_count ?: $product->reviews->where('status', 'approved')->count())),
                'bestRating' => '5',
                'worstRating' => '1',
            ] : null,
            'review' => $reviews ?: null,
        ], fn ($value) => ! is_null($value) && $value !== '' && $value !== []);
    }

    private function articleSchema(Blog $blog, string $canonical, string $description, ?string $image): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $blog->title,
            'description' => $description,
            'image' => $image ? [$image] : [$this->defaultImage()],
            'url' => $canonical,
            'datePublished' => optional($blog->published_at ?: $blog->created_at)->toAtomString(),
            'dateModified' => optional($blog->updated_at)->toAtomString(),
            'author' => ['@type' => 'Organization', 'name' => $this->organizationName()],
            'publisher' => $this->organizationSchema(),
            'keywords' => collect($blog->tags)->implode(', ') ?: null,
        ]);
    }

    private function breadcrumbSchema(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn ($item, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ])->all(),
        ];
    }

    private function faqSchema(array $faqs): array
    {
        $items = collect($faqs)
            ->filter(fn ($faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($faq['answer'])],
            ])
            ->values()
            ->all();

        return $items ? ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items] : [];
    }

    private function baseSchemas(): array
    {
        $schemas = [$this->organizationSchema()];

        $website = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $this->siteName(),
            'url' => url('/'),
        ];

        if (Setting::bool(Setting::SEO_SEARCH_ACTION_ENABLED, true)) {
            $website['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => route('frontend.search') . '?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ];
        }

        $schemas[] = $website;

        return $schemas;
    }

    private function organizationSchema(): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $this->organizationName(),
            'url' => url('/'),
            'logo' => $this->organizationLogo(),
            'email' => Setting::get(Setting::SEO_ORGANIZATION_EMAIL),
            'telephone' => Setting::get(Setting::SEO_ORGANIZATION_PHONE),
            'sameAs' => $this->socialLinks(),
        ], fn ($value) => ! is_null($value) && $value !== '' && $value !== []);
    }

    private function webPageSchema(string $title, string $canonical, ?string $description): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $title,
            'url' => $canonical,
            'description' => $description ? $this->cleanDescription($description) : null,
        ]);
    }

    private function collectionPageSchema(string $title, string $description, string $canonical): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $title,
            'description' => $this->cleanDescription($description),
            'url' => $canonical,
        ];
    }

    private function imageObjectSchemas(Product $product): array
    {
        return $this->productImages($product)
            ->map(fn ($image) => array_filter([
                '@context' => 'https://schema.org',
                '@type' => 'ImageObject',
                'contentUrl' => $image['url'],
                'name' => $image['title'] ?: $product->name,
                'caption' => $image['caption'],
                'description' => $image['description'],
                'width' => $image['width'],
                'height' => $image['height'],
            ]))
            ->values()
            ->all();
    }

    private function productBreadcrumbs(Product $product, string $canonical): array
    {
        $items = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Shop', 'url' => route('shop')],
        ];

        if ($product->category) {
            $items[] = ['name' => $product->category->name, 'url' => route('shop.category', ['category' => $product->category->slug])];
        }

        $items[] = ['name' => $product->name, 'url' => $canonical];

        return $items;
    }

    private function categoryBreadcrumbs(Category $category, string $canonical): array
    {
        $items = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Shop', 'url' => route('shop')],
        ];

        $trail = collect();
        $current = $category;
        while ($current) {
            $trail->prepend($current);
            $current = $current->parent;
        }

        foreach ($trail as $item) {
            $items[] = [
                'name' => $item->name,
                'url' => (int) $item->id === (int) $category->id ? $canonical : route('shop.category', ['category' => $item->slug]),
            ];
        }

        return $items;
    }

    private function productImages(Product $product): Collection
    {
        return $product->images
            ->sortBy([
                ['featured', 'desc'],
                ['sort_order', 'asc'],
                ['id', 'asc'],
            ])
            ->map(function ($image) use ($product) {
                $path = $image->path;

                return [
                    'url' => $path ? asset('storage/' . ltrim($path, '/')) : null,
                    'alt' => $image->alt ?: $this->generatedProductAlt($product),
                    'title' => $image->title,
                    'caption' => $image->caption,
                    'description' => $image->description,
                    'width' => $image->width,
                    'height' => $image->height,
                ];
            })
            ->filter(fn ($image) => filled($image['url']))
            ->values();
    }

    private function productImage(Product $product): ?string
    {
        return $this->productImages($product)->first()['url'] ?? null;
    }

    private function productImageAlt(Product $product): string
    {
        return $this->productImages($product)->first()['alt'] ?? $this->generatedProductAlt($product);
    }

    private function generatedProductAlt(Product $product): string
    {
        return collect([$product->name, $product->material?->name, $product->category?->name])
            ->filter()
            ->unique()
            ->implode(' - ');
    }

    private function canonicalForCurrentRequest(): string
    {
        if (request()->filled('page') && request()->integer('page') > 1) {
            return request()->fullUrlWithQuery(['page' => request()->integer('page')]);
        }

        return url()->current();
    }

    private function currentRequestShouldNoIndex(): bool
    {
        $routeName = (string) request()->route()?->getName();
        $path = trim(request()->path(), '/');
        $privatePrefixes = ['cart', 'checkout', 'dashboard', 'my-account', 'my-profile', 'edit-account', 'wishlist', 'search'];

        if (Str::startsWith($routeName, ['admin.', 'vendor.'])) {
            return true;
        }

        if (collect($privatePrefixes)->contains(fn ($prefix) => Str::startsWith($path, $prefix))) {
            return true;
        }

        return collect(request()->query())->keys()->reject(fn ($key) => $key === 'page')->isNotEmpty();
    }

    private function formatTitle(string $title): string
    {
        $title = trim(preg_replace('/\s+/', ' ', strip_tags($title)));
        $suffix = trim((string) Setting::get(Setting::SEO_TITLE_SUFFIX, $this->siteName()));

        if ($suffix === '' || Str::contains(Str::lower($title), Str::lower($suffix))) {
            return $title ?: $this->siteName();
        }

        return ($title ?: $this->siteName()) . ' | ' . $suffix;
    }

    private function cleanDescription(string $description): string
    {
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags($description)));

        return Str::limit($clean ?: $this->defaultDescription(), 165, '');
    }

    private function defaultImage(): string
    {
        $path = Setting::get(Setting::SEO_DEFAULT_OG_IMAGE_PATH)
            ?: Setting::get(Setting::SEO_ORGANIZATION_LOGO_PATH)
            ?: Setting::get(Setting::SITE_LOGO_PATH);

        return $path ? asset('storage/' . ltrim($path, '/')) : asset('assets/img/logo.svg');
    }

    private function organizationLogo(): string
    {
        $path = Setting::get(Setting::SEO_ORGANIZATION_LOGO_PATH) ?: Setting::get(Setting::SITE_LOGO_PATH);

        return $path ? asset('storage/' . ltrim($path, '/')) : asset('assets/img/logo.svg');
    }

    private function siteName(): string
    {
        return Setting::get(Setting::SITE_NAME, config('app.name', 'Furniture Store')) ?: config('app.name', 'Furniture Store');
    }

    private function organizationName(): string
    {
        return Setting::get(Setting::SEO_ORGANIZATION_NAME, $this->siteName()) ?: $this->siteName();
    }

    private function defaultDescription(): string
    {
        return Setting::get(Setting::SEO_DEFAULT_DESCRIPTION, 'Premium furniture and home decor crafted for modern homes.');
    }

    private function socialLinks(): array
    {
        $links = Setting::get(Setting::SEO_SOCIAL_LINKS, '');

        return collect(preg_split('/[\r\n,]+/', (string) $links) ?: [])
            ->map(fn ($link) => trim($link))
            ->filter(fn ($link) => filter_var($link, FILTER_VALIDATE_URL))
            ->values()
            ->all();
    }
}
