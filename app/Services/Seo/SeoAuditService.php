<?php

namespace App\Services\Seo;

use App\Models\Blog;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SeoAuditService
{
    public function dashboard(): array
    {
        $productCount = Product::query()->where('status', 'active')->count();
        $categoryCount = Category::query()->where('status', 'active')->count();
        $cmsCount = CmsPage::query()->active()->count();

        $productsMissingTitle = Product::query()
            ->where('status', 'active')
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->whereNotNull('title')->where('title', '<>', ''))
            ->where(fn ($query) => $query->whereNull('seo_title')->orWhere('seo_title', ''))
            ->count();

        $productsMissingDescription = Product::query()
            ->where('status', 'active')
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->whereNotNull('description')->where('description', '<>', ''))
            ->where(fn ($query) => $query->whereNull('seo_description')->orWhere('seo_description', ''))
            ->count();

        $imagesMissingAlt = ProductImage::query()
            ->whereHas('product', fn ($query) => $query->where('status', 'active'))
            ->where(fn ($query) => $query->whereNull('alt')->orWhere('alt', ''))
            ->count();

        $categoriesMissingMeta = Category::query()
            ->where('status', 'active')
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->whereNotNull('title')->where('title', '<>', '')->whereNotNull('description')->where('description', '<>', ''))
            ->where(fn ($query) => $query
                ->whereNull('meta_title')
                ->orWhereNull('meta_description')
                ->orWhere('meta_title', '')
                ->orWhere('meta_description', ''))
            ->count();

        $cmsMissingMeta = CmsPage::query()
            ->active()
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->whereNotNull('title')->where('title', '<>', '')->whereNotNull('description')->where('description', '<>', ''))
            ->where(fn ($query) => $query
                ->whereNull('meta_title')
                ->orWhereNull('meta_description')
                ->orWhere('meta_title', '')
                ->orWhere('meta_description', ''))
            ->count();

        $noindexedPages = SeoMetadata::query()->where('robots_index', false)->count();
        $duplicateTitles = $this->duplicateGroupCount('title');
        $duplicateDescriptions = $this->duplicateGroupCount('description');
        $duplicateCanonicals = $this->duplicateGroupCount('canonical_url');

        $auditedProducts = Product::query()
            ->with(['images', 'seoMetadata'])
            ->where('status', 'active')
            ->get();
        $productAverageScore = $auditedProducts->isNotEmpty()
            ? (int) round($auditedProducts->avg(fn (Product $product) => $this->productScore($product)))
            : 100;
        $issueCount = $productsMissingTitle
            + $productsMissingDescription
            + $imagesMissingAlt
            + $categoriesMissingMeta
            + $cmsMissingMeta
            + $noindexedPages
            + ($duplicateTitles * 2)
            + ($duplicateDescriptions * 2)
            + ($duplicateCanonicals * 2);
        $issueBudget = max(1, ($productCount * 3) + $categoryCount + $cmsCount + 5);
        $issueScore = max(0, 100 - min(100, (int) round(($issueCount / $issueBudget) * 100)));
        $overallScore = (int) round(($productAverageScore * 0.65) + ($issueScore * 0.35));

        return [
            'score' => $overallScore,
            'score_breakdown' => [
                'product_average' => $productAverageScore,
                'issue_health' => $issueScore,
                'open_issue_count' => $issueCount,
            ],
            'cards' => [
                ['label' => 'Active products', 'value' => $productCount, 'tone' => 'blue'],
                ['label' => 'Products missing SEO title', 'value' => $productsMissingTitle, 'tone' => $productsMissingTitle ? 'red' : 'green'],
                ['label' => 'Products missing SEO description', 'value' => $productsMissingDescription, 'tone' => $productsMissingDescription ? 'red' : 'green'],
                ['label' => 'Images missing alt text', 'value' => $imagesMissingAlt, 'tone' => $imagesMissingAlt ? 'amber' : 'green'],
                ['label' => 'Categories missing meta', 'value' => $categoriesMissingMeta, 'tone' => $categoriesMissingMeta ? 'amber' : 'green'],
                ['label' => 'CMS pages missing meta', 'value' => $cmsMissingMeta, 'tone' => $cmsMissingMeta ? 'amber' : 'green'],
                ['label' => 'Noindexed SEO records', 'value' => $noindexedPages, 'tone' => $noindexedPages ? 'amber' : 'green'],
                ['label' => 'Duplicate SEO titles', 'value' => $duplicateTitles, 'tone' => $duplicateTitles ? 'red' : 'green'],
                ['label' => 'Duplicate descriptions', 'value' => $duplicateDescriptions, 'tone' => $duplicateDescriptions ? 'red' : 'green'],
                ['label' => 'Duplicate canonicals', 'value' => $duplicateCanonicals, 'tone' => $duplicateCanonicals ? 'red' : 'green'],
            ],
            'products' => Product::query()
                ->with(['category', 'images', 'seoMetadata'])
                ->where('status', 'active')
                ->latest()
                ->limit(12)
                ->get()
                ->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'url' => route('product-details', $product->slug),
                    'admin_url' => route('admin.products.edit', $product),
                    'score' => $this->productScore($product),
                    'issues' => $this->productIssues($product),
                ]),
            'bulk' => [
                'missing_product_titles' => $this->missingProductTitles(),
                'missing_product_descriptions' => $this->missingProductDescriptions(),
                'missing_image_alt' => $this->missingImageAlt(),
                'duplicate_titles' => $this->duplicateRecords('title'),
                'duplicate_descriptions' => $this->duplicateRecords('description'),
                'duplicate_canonicals' => $this->duplicateRecords('canonical_url'),
                'noindexed' => $this->noindexedRecords(),
            ],
        ];
    }

    public function health(?string $homeUrl = null): array
    {
        $homeUrl = $this->normalizeBaseUrl($homeUrl ?: url('/'));
        $pageSpeedUrl = $this->pageSpeedTargetUrl($homeUrl);
        $started = microtime(true);
        $response = null;
        $error = null;

        try {
            $response = Http::timeout(8)->get($homeUrl);
        } catch (\Throwable $exception) {
            $error = $this->redact($exception->getMessage());
        }

        $durationMs = (int) round((microtime(true) - $started) * 1000);
        $html = $response?->body() ?? '';
        $pageSpeed = $this->pageSpeed($pageSpeedUrl);

        $checks = [
            $this->check('Home responds successfully', $response?->successful() === true, $response?->status() ? 'HTTP ' . $response->status() : $error),
            $this->check('Response under 1.5 seconds', $durationMs <= 1500, $durationMs . ' ms'),
            $this->check('Title tag exists', Str::contains($html, '<title>'), null),
            $this->check('Meta description exists', Str::contains($html, 'name="description"'), null),
            $this->check('Canonical tag exists', Str::contains($html, 'rel="canonical"'), null),
            $this->check('Open Graph tags exist', Str::contains($html, 'property="og:title"'), null),
            $this->check('JSON-LD schema exists', Str::contains($html, 'application/ld+json'), null),
            $this->check('Robots endpoint available', $this->endpointOk(url('/robots.txt')), url('/robots.txt')),
            $this->check('Sitemap index available', $this->endpointOk(url('/sitemap.xml')), url('/sitemap.xml')),
            $this->check('Product sitemap available', $this->endpointOk(url('/sitemap-products.xml')), url('/sitemap-products.xml')),
            $this->check('Category sitemap available', $this->endpointOk(url('/sitemap-categories.xml')), url('/sitemap-categories.xml')),
            $this->check('Page sitemap available', $this->endpointOk(url('/sitemap-pages.xml')), url('/sitemap-pages.xml')),
            $this->check('Image sitemap available', $this->endpointOk(url('/sitemap-images.xml')), url('/sitemap-images.xml')),
        ];

        return [
            'url' => $homeUrl,
            'pagespeed_url' => $pageSpeedUrl,
            'status' => $response?->status(),
            'error' => $error,
            'response_ms' => $durationMs,
            'size_kb' => $html !== '' ? round(strlen($html) / 1024, 1) : null,
            'pagespeed' => $pageSpeed,
            'score' => $this->healthScore($checks, $pageSpeed),
            'checked_at' => now()->toDateTimeString(),
            'checks' => $checks,
        ];
    }

    public function pendingHealth(?string $homeUrl = null): array
    {
        $homeUrl = $this->normalizeBaseUrl($homeUrl ?: url('/'));

        return [
            'url' => $homeUrl,
            'pagespeed_url' => $this->pageSpeedTargetUrl($homeUrl),
            'status' => null,
            'error' => null,
            'response_ms' => null,
            'size_kb' => null,
            'score' => null,
            'checked_at' => null,
            'pagespeed' => [
                'enabled' => false,
                'api_key_present' => (bool) (config('services.pagespeed.key') ?: env('PAGESPEED_API_KEY')),
                'target_url' => $this->pageSpeedTargetUrl($homeUrl),
                'message' => 'Website health scan has been queued. Start the queue worker to process it in the background.',
            ],
            'checks' => [],
        ];
    }

private function pageSpeed(string $url): array
{
    $apiKey = config('services.pagespeed.key') ?: env('PAGESPEED_API_KEY');

    if (! $apiKey) {
        return [
            'enabled' => false,
            'api_key_present' => false,
            'target_url' => $url,
            'message' => 'Set PAGESPEED_API_KEY in .env to enable live PageSpeed Insights.',
        ];
    }

    if (! filter_var($url, FILTER_VALIDATE_URL)) {
        return [
            'enabled' => true,
            'api_key_present' => true,
            'target_url' => $url,
            'error' => 'PageSpeed target URL is invalid.',
        ];
    }

    if (! $this->isPubliclyAuditableUrl($url)) {
        return [
            'enabled' => true,
            'api_key_present' => true,
            'target_url' => $url,
            'skipped' => true,
            'message' => 'PageSpeed API key is detected, but Google cannot audit localhost/private URLs. Set PAGESPEED_TARGET_URL to a public live/ngrok URL.',
        ];
    }

    $results = [
        'enabled' => true,
        'api_key_present' => true,
        'target_url' => $url,
        'strategy' => 'mobile',
        'ssl_retry' => false,
    ];

    foreach (['mobile', 'desktop'] as $strategy) {
        try {
            $response = $this->pageSpeedRequest($url, $apiKey, $this->verifyPageSpeedSsl(), $strategy);
        } catch (\Throwable $exception) {
            if ($this->isSslCertificateError($exception) && app()->environment('local')) {
                try {
                    $response = $this->pageSpeedRequest($url, $apiKey, false, $strategy);
                    $results['ssl_retry'] = true;
                } catch (\Throwable $retryException) {
                    $results[$strategy] = [
                        'error' => $this->redact($retryException->getMessage(), $apiKey),
                    ];
                    continue;
                }
            } else {
                $results[$strategy] = [
                    'error' => $this->redact($exception->getMessage(), $apiKey),
                ];
                continue;
            }
        }

        if (! $response->successful()) {
            $message = data_get($response->json(), 'error.message')
                ?: Str::limit($response->body(), 300, '');

            $results[$strategy] = [
                'error' => 'PageSpeed request failed with HTTP ' . $response->status() . ': ' . $this->redact($message, $apiKey),
            ];

            continue;
        }

        $categories = data_get($response->json(), 'lighthouseResult.categories', []);

        if ($categories === []) {
            $results[$strategy] = [
                'error' => 'PageSpeed returned no Lighthouse categories for this URL.',
            ];

            continue;
        }

        $results[$strategy] = [
            'scores' => collect($categories)->mapWithKeys(fn ($category, $key) => [
                $key => (int) round((float) data_get($category, 'score', 0) * 100),
            ])->all(),
            'metrics' => [
                'first-contentful-paint' => data_get($response->json(), 'lighthouseResult.audits.first-contentful-paint.displayValue'),
                'largest-contentful-paint' => data_get($response->json(), 'lighthouseResult.audits.largest-contentful-paint.displayValue'),
                'speed-index' => data_get($response->json(), 'lighthouseResult.audits.speed-index.displayValue'),
                'total-blocking-time' => data_get($response->json(), 'lighthouseResult.audits.total-blocking-time.displayValue'),
            ],
        ];
    }

    $results['scores'] = $results['mobile']['scores'] ?? [];
    $results['metrics'] = $results['mobile']['metrics'] ?? [];

    return $results;
}

    private function productScore(Product $product): int
    {
        return app(SeoManager::class)->score(
            $this->seoTitle($product),
            $this->seoDescription($product),
            $product->seoMetadata?->focus_keyword,
            $this->productHasSeoReadyImages($product),
            true,
            filled($product->seoMetadata?->canonical_url) || filled($product->slug)
        );
    }

    private function productIssues(Product $product): array
    {
        $issues = [];
        $title = $this->seoTitle($product);
        $description = $this->seoDescription($product);
        $focusKeyword = trim((string) $product->seoMetadata?->focus_keyword);
        $titleLength = mb_strlen($title);
        $descriptionLength = mb_strlen($description);

        if ($title === '') {
            $issues[] = 'SEO title missing';
        } elseif ($titleLength < 45 || $titleLength > 60) {
            $issues[] = "Title length {$titleLength}, ideal 45-60";
        }

        if ($description === '') {
            $issues[] = 'SEO description missing';
        } elseif ($descriptionLength < 120 || $descriptionLength > 160) {
            $issues[] = "Description length {$descriptionLength}, ideal 120-160";
        }

        if ($focusKeyword === '') {
            $issues[] = 'Focus keyword missing';
        } else {
            $keyword = Str::lower($focusKeyword);
            if (! Str::contains(Str::lower($title), $keyword)) {
                $issues[] = 'Focus keyword missing from title';
            }
            if (! Str::contains(Str::lower($description), $keyword)) {
                $issues[] = 'Focus keyword missing from description';
            }
        }

        if ($product->images->isEmpty()) {
            $issues[] = 'Product image missing';
        } elseif (! $this->productHasSeoReadyImages($product)) {
            $issues[] = 'Some images need alt text';
        }

        if (! filled($product->slug)) {
            $issues[] = 'Slug missing';
        }

        if ($description !== '' && $this->duplicateValueExists('description', $description, $product->seoMetadata?->id)) {
            $issues[] = 'Duplicate SEO description';
        }

        if ($issues === [] && $this->productScore($product) < 80) {
            $issues[] = 'Needs SEO tuning';
        }

        return $issues;
    }

    private function missingProductTitles()
    {
        return Product::query()
            ->with('seoMetadata')
            ->where('status', 'active')
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->whereNotNull('title')->where('title', '<>', ''))
            ->where(fn ($query) => $query->whereNull('seo_title')->orWhere('seo_title', ''))
            ->limit(20)
            ->get()
            ->map(fn ($product) => ['label' => $product->name, 'url' => route('admin.products.edit', $product)]);
    }

    private function missingProductDescriptions()
    {
        return Product::query()
            ->with('seoMetadata')
            ->where('status', 'active')
            ->whereDoesntHave('seoMetadata', fn ($query) => $query->whereNotNull('description')->where('description', '<>', ''))
            ->where(fn ($query) => $query->whereNull('seo_description')->orWhere('seo_description', ''))
            ->limit(20)
            ->get()
            ->map(fn ($product) => ['label' => $product->name, 'url' => route('admin.products.edit', $product)]);
    }

    private function missingImageAlt()
    {
        return ProductImage::query()
            ->with('product')
            ->whereHas('product', fn ($query) => $query->where('status', 'active'))
            ->where(fn ($query) => $query->whereNull('alt')->orWhere('alt', ''))
            ->limit(20)
            ->get()
            ->map(fn ($image) => [
                'label' => ($image->product?->name ?: 'Product') . ' image #' . $image->id,
                'url' => $image->product ? route('admin.products.edit', $image->product) : '#',
            ]);
    }

    private function noindexedRecords()
    {
        return SeoMetadata::query()
            ->where('robots_index', false)
            ->limit(20)
            ->get()
            ->map(fn ($record) => [
                'label' => $this->seoRecordLabel($record),
                'url' => $this->seoRecordAdminUrl($record),
            ]);
    }

    private function duplicateRecords(string $column)
    {
        $values = SeoMetadata::query()
            ->select($column)
            ->whereNotNull($column)
            ->where($column, '<>', '')
            ->groupBy($column)
            ->havingRaw('COUNT(*) > 1')
            ->pluck($column);

        if ($values->isEmpty()) {
            return collect();
        }

        return SeoMetadata::query()
            ->whereIn($column, $values)
            ->limit(20)
            ->get()
            ->map(fn (SeoMetadata $record) => [
                'label' => $this->seoRecordLabel($record),
                'url' => $this->seoRecordAdminUrl($record),
            ]);
    }

    private function duplicateGroupCount(string $column): int
    {
        return SeoMetadata::query()
            ->select($column)
            ->whereNotNull($column)
            ->where($column, '<>', '')
            ->groupBy($column)
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();
    }

    private function duplicateValueExists(string $column, string $value, ?int $ignoreId): bool
    {
        return SeoMetadata::query()
            ->where($column, $value)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists();
    }

    private function seoTitle(Product $product): string
    {
        return trim((string) ($product->seoMetadata?->title ?: $product->seo_title ?: $product->name));
    }

    private function seoDescription(Product $product): string
    {
        return trim((string) ($product->seoMetadata?->description ?: $product->seo_description ?: $product->short_description));
    }

    private function productHasSeoReadyImages(Product $product): bool
    {
        return $product->images->isNotEmpty()
            && $product->images->every(fn ($image) => filled($image->alt));
    }

    private function seoRecordLabel(SeoMetadata $record): string
    {
        $model = $record->seoable;

        if ($model instanceof Product) {
            return 'Product: ' . $model->name;
        }
        if ($model instanceof Category) {
            return 'Category: ' . $model->name;
        }
        if ($model instanceof Blog) {
            return 'Blog: ' . $model->title;
        }
        if ($model instanceof CmsPage) {
            return 'CMS: ' . $model->title;
        }

        return $record->page_key ?: class_basename((string) $record->seoable_type) . ' #' . $record->seoable_id;
    }

    private function seoRecordAdminUrl(SeoMetadata $record): string
    {
        $model = $record->seoable;

        return match (true) {
            $model instanceof Product => route('admin.products.edit', $model),
            $model instanceof Category => route('admin.categories.edit', $model),
            $model instanceof Blog => route('admin.blogs.edit', $model),
            $model instanceof CmsPage => route('admin.cms.edit', $model),
            default => route('admin.seo.pages'),
        };
    }

    private function pageSpeedRequest(string $url, string $apiKey, bool $verifySsl, string $strategy = 'mobile')
{
    $request = Http::timeout(30);

    if (! $verifySsl) {
        $request = $request->withoutVerifying();
    }

    $endpoint = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed'
        . '?category=performance&category=seo&category=accessibility&category=best-practices';

    return $request->get($endpoint, [
        'url' => $url,
        'key' => $apiKey,
        'strategy' => $strategy,
    ]);
}

    private function pageSpeedTargetUrl(string $fallback): string
    {
        return trim((string) (config('services.pagespeed.url') ?: env('PAGESPEED_TARGET_URL') ?: $fallback));
    }

    private function normalizeBaseUrl(string $url): string
    {
        return rtrim(trim($url), '/') ?: url('/');
    }

    private function verifyPageSpeedSsl(): bool
    {
        return filter_var(config('services.pagespeed.verify_ssl', true), FILTER_VALIDATE_BOOLEAN);
    }

    private function isPubliclyAuditableUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return false;
        }

        $host = Str::lower($host);

        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true) || Str::endsWith($host, ['.local', '.test'])) {
            return false;
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
        }

        return true;
    }

    private function isSslCertificateError(\Throwable $exception): bool
    {
        return Str::contains($exception->getMessage(), ['cURL error 60', 'SSL certificate problem']);
    }

    private function endpointOk(string $url): bool
    {
        try {
            return Http::timeout(5)->get($url)->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    private function check(string $label, bool $passed, ?string $detail): array
    {
        return compact('label', 'passed', 'detail');
    }

    private function healthScore(array $checks, array $pageSpeed): int
    {
        $checkScore = count($checks) > 0
            ? (int) round((collect($checks)->where('passed', true)->count() / count($checks)) * 100)
            : 0;

        $pageSpeedScores = collect($pageSpeed['scores'] ?? [])->filter(fn ($score) => is_numeric($score));

        if ($pageSpeedScores->isEmpty()) {
            return $checkScore;
        }

        return (int) round(($checkScore * 0.65) + ($pageSpeedScores->avg() * 0.35));
    }

    private function redact(string $message, ?string $apiKey = null): string
    {
        if ($apiKey) {
            $message = str_replace($apiKey, '[redacted]', $message);
        }

        return preg_replace('/key=([^&\s]+)/', 'key=[redacted]', $message) ?: $message;
    }
}
