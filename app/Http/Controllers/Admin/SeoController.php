<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\RefreshWebsiteHealth;
use App\Models\SeoMetadata;
use App\Models\Setting;
use App\Services\Seo\SeoAuditService;
use App\Services\Seo\SeoMetadataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function index(SeoAuditService $auditService)
    {
        $audit = $auditService->dashboard();

        return view('admin.seo.index', compact('audit'));
    }

    public function health(Request $request, SeoAuditService $auditService)
    {
        if ($request->boolean('refresh') || ! Cache::has('seo.website_health.result')) {
            $this->queueWebsiteHealthRefresh($request->getSchemeAndHttpHost());
        }

        $health = Cache::get('seo.website_health.result') ?: $auditService->pendingHealth($request->getSchemeAndHttpHost());
        $health['refresh_status'] = Cache::get('seo.website_health.status', [
            'state' => Cache::has('seo.website_health.running') ? 'running' : 'pending',
        ]);
        $health['is_running'] = Cache::has('seo.website_health.running');

        return view('admin.seo.health', compact('health'));
    }

    public function refreshHealth()
    {
        $this->queueWebsiteHealthRefresh(request()->getSchemeAndHttpHost());

        return redirect()->route('admin.seo.health')->with('success', 'Website health scan queued. Results will update after the queue worker processes it.');
    }

    public function healthStatus()
    {
        $status = Cache::get('seo.website_health.status', [
            'state' => Cache::has('seo.website_health.running') ? 'running' : 'pending',
        ]);
        $health = Cache::get('seo.website_health.result');

        return response()->json([
            'state' => $status['state'] ?? 'pending',
            'is_running' => Cache::has('seo.website_health.running'),
            'queued_at' => $status['queued_at'] ?? null,
            'started_at' => $status['started_at'] ?? null,
            'finished_at' => $status['finished_at'] ?? null,
            'error' => $status['error'] ?? null,
            'score' => $health['score'] ?? null,
            'cached_at' => $health['cached_at'] ?? $health['checked_at'] ?? null,
            'has_result' => (bool) $health,
        ]);
    }

    public function settings()
    {
        $settings = collect($this->settingKeys())->mapWithKeys(fn ($default, $key) => [
            $key => old($key, Setting::get($key, $default)),
        ]);

        return view('admin.seo.settings', ['settings' => $settings]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            Setting::SEO_TITLE_SUFFIX => 'nullable|string|max:120',
            Setting::SEO_DEFAULT_DESCRIPTION => 'nullable|string|max:500',
            Setting::SEO_DEFAULT_OG_IMAGE_PATH => 'nullable|string|max:255',
            Setting::SEO_ORGANIZATION_NAME => 'nullable|string|max:255',
            Setting::SEO_ORGANIZATION_LOGO_PATH => 'nullable|string|max:255',
            Setting::SEO_ORGANIZATION_PHONE => 'nullable|string|max:50',
            Setting::SEO_ORGANIZATION_EMAIL => 'nullable|email|max:255',
            Setting::SEO_SOCIAL_LINKS => 'nullable|string|max:2000',
            Setting::SEO_SEARCH_ACTION_ENABLED => 'nullable|boolean',
            Setting::SEO_DEFAULT_ROBOTS_INDEX => 'nullable|boolean',
            Setting::SEO_DEFAULT_ROBOTS_FOLLOW => 'nullable|boolean',
            Setting::SEO_SITEMAP_PRODUCTS_PRIORITY => 'nullable|numeric|min:0.1|max:1',
            Setting::SEO_SITEMAP_CATEGORIES_PRIORITY => 'nullable|numeric|min:0.1|max:1',
            Setting::SEO_SITEMAP_PAGES_PRIORITY => 'nullable|numeric|min:0.1|max:1',
            Setting::SEO_ROBOTS_EXTRA_DISALLOW => 'nullable|string|max:2000',
        ]);

        foreach ($this->settingKeys() as $key => $default) {
            if (in_array($key, [Setting::SEO_SEARCH_ACTION_ENABLED, Setting::SEO_DEFAULT_ROBOTS_INDEX, Setting::SEO_DEFAULT_ROBOTS_FOLLOW], true)) {
                $value = $request->boolean($key) ? '1' : '0';
            } else {
                $value = array_key_exists($key, $validated) ? $validated[$key] : $default;
            }

            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'seo', 'type' => 'text']
            );
        }

        return redirect()->route('admin.seo.settings')->with('success', 'SEO settings updated successfully.');
    }

    public function pages()
    {
        $pages = collect($this->pageDefinitions())->map(function ($page) {
            $page['metadata'] = SeoMetadata::query()->where('page_key', $page['key'])->first();

            return $page;
        });

        return view('admin.seo.pages', compact('pages'));
    }

    public function updatePage(string $pageKey, Request $request, SeoMetadataService $metadataService)
    {
        abort_unless(collect($this->pageDefinitions())->contains('key', $pageKey), 404);

        $validated = $this->validateSeoMetadata($request);
        $metadataService->syncForPage($pageKey, $validated['seo_meta'] ?? []);

        return redirect()->route('admin.seo.pages')->with('success', 'Page SEO updated successfully.');
    }

    public function pageDefinitions(): array
    {
        return [
            ['key' => 'home', 'label' => 'Home', 'url' => url('/')],
            ['key' => 'shop', 'label' => 'Shop', 'url' => route('shop')],
            ['key' => 'blog', 'label' => 'Blog Listing', 'url' => route('blog.index')],
            ['key' => 'contact', 'label' => 'Contact', 'url' => url('/contact')],
            ['key' => 'about', 'label' => 'About', 'url' => url('/about')],
            ['key' => 'faq', 'label' => 'FAQ', 'url' => url('/faq')],
            ['key' => 'terms-and-conditions', 'label' => 'Terms and Conditions', 'url' => url('/terms-and-conditions')],
            ['key' => 'return-policy', 'label' => 'Return Policy', 'url' => url('/return-policy')],
            ['key' => 'pricing', 'label' => 'Pricing', 'url' => url('/pricing')],
            ['key' => 'team', 'label' => 'Team', 'url' => url('/team')],
            ['key' => 'our-clients', 'label' => 'Our Clients', 'url' => url('/our-clients')],
            ['key' => 'store.index', 'label' => 'Store', 'url' => route('store.index')],
            ['key' => 'quotation-products.index', 'label' => 'Quotation Products', 'url' => route('quotation-products.index')],
            ['key' => 'portfolio-v1', 'label' => 'Portfolio', 'url' => url('/portfolio-v1')],
        ];
    }

    private function validateSeoMetadata(Request $request): array
    {
        return $request->validate([
            'seo_meta' => 'nullable|array',
            'seo_meta.title' => 'nullable|string|max:255',
            'seo_meta.description' => 'nullable|string|max:500',
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
    }

    private function settingKeys(): array
    {
        return [
            Setting::SEO_TITLE_SUFFIX => Setting::get(Setting::SITE_NAME, config('app.name')),
            Setting::SEO_DEFAULT_DESCRIPTION => 'Premium furniture and home decor crafted for modern homes.',
            Setting::SEO_DEFAULT_OG_IMAGE_PATH => '',
            Setting::SEO_ORGANIZATION_NAME => Setting::get(Setting::SITE_NAME, config('app.name')),
            Setting::SEO_ORGANIZATION_LOGO_PATH => '',
            Setting::SEO_ORGANIZATION_PHONE => '',
            Setting::SEO_ORGANIZATION_EMAIL => '',
            Setting::SEO_SOCIAL_LINKS => '',
            Setting::SEO_SEARCH_ACTION_ENABLED => '1',
            Setting::SEO_DEFAULT_ROBOTS_INDEX => '1',
            Setting::SEO_DEFAULT_ROBOTS_FOLLOW => '1',
            Setting::SEO_SITEMAP_PRODUCTS_PRIORITY => '0.8',
            Setting::SEO_SITEMAP_CATEGORIES_PRIORITY => '0.7',
            Setting::SEO_SITEMAP_PAGES_PRIORITY => '0.6',
            Setting::SEO_ROBOTS_EXTRA_DISALLOW => '',
        ];
    }

    private function queueWebsiteHealthRefresh(?string $homeUrl = null): void
    {
        if (! Cache::add('seo.website_health.running', true, now()->addMinutes(10))) {
            return;
        }

        Cache::put('seo.website_health.status', [
            'state' => 'queued',
            'queued_at' => now()->toDateTimeString(),
        ], now()->addMinutes(10));

        RefreshWebsiteHealth::dispatch($homeUrl);
    }
}
