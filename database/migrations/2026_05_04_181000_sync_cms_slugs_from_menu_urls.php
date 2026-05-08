<?php

use App\Models\CmsPage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menus') || ! Schema::hasTable('cms_pages')) {
            return;
        }

        $menus = DB::table('menus')
            ->whereIn('menu_type', ['header_main', 'footer_sitemap', 'footer_others', 'footer_service'])
            ->where('status', 'active')
            ->whereNotNull('url')
            ->get();

        foreach ($menus as $menu) {
            $url = $this->normalizeUrl($menu->url);

            if (! $this->isLocalPageUrl($url)) {
                continue;
            }

            $targetSlug = trim($url, '/');

            if ($targetSlug === '' || CmsPage::query()->where('slug', $targetSlug)->exists()) {
                continue;
            }

            $page = $this->matchingPageForMenu($menu);

            if (! $page) {
                continue;
            }

            $oldSlug = $page->slug;
            $page->update(['slug' => $targetSlug]);

            DB::table('menus')
                ->where('url', '/' . ltrim($oldSlug, '/'))
                ->update(['url' => $url]);
        }
    }

    public function down(): void
    {
        //
    }

    private function matchingPageForMenu(object $menu): ?CmsPage
    {
        $titleSlug = Str::slug((string) $menu->title);
        $candidates = collect([
            $titleSlug,
            Str::replaceLast('-us', '', $titleSlug),
            Str::replaceLast('-page', '', $titleSlug),
        ])->filter()->unique()->values();

        return CmsPage::query()
            ->whereIn('slug', $candidates)
            ->orWhereIn(DB::raw('LOWER(title)'), $candidates->map(fn ($slug) => Str::replace('-', ' ', $slug))->all())
            ->first();
    }

    private function normalizeUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://', '#'])) {
            return $url;
        }

        return '/' . Str::slug(trim($url, '/'));
    }

    private function isLocalPageUrl(?string $url): bool
    {
        return filled($url) && Str::startsWith($url, '/') && ! Str::startsWith($url, ['//', '/admin', '/panel', '/api', '/storage']);
    }
};
