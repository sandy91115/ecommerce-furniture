<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\CmsPage;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
=======
use App\Models\Menu;
use Illuminate\Http\Request;
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

class MenuController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $this->authorize('menus.view');

        $type = $request->get('type', 'all');
        $typeOrder = [
            'admin_sidebar' => 1,
            'header_main' => 2,
            'footer_shop' => 3,
            'footer_sitemap' => 4,
            'footer_others' => 5,
            'footer_service' => 6,
        ];

        $menuGroups = Menu::when($type !== 'all', fn($q) => $q->type($type))
            ->orderByRaw(
                'CASE menu_type ' . collect($typeOrder)
                    ->map(fn ($position, $menuType) => "WHEN '{$menuType}' THEN {$position}")
                    ->implode(' ') . ' ELSE 99 END'
            )
=======
        $type = $request->get('type', 'all');
        $menuGroups = Menu::when($type !== 'all', fn($q) => $q->type($type))
            ->active()
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            ->orderBy('order')
            ->orderBy('id')
            ->with('children')
            ->get()
            ->groupBy('menu_type');

<<<<<<< HEAD
        $menuTypes = Menu::distinct('menu_type')
            ->pluck('menu_type')
            ->merge(['header_main', 'footer_sitemap', 'footer_others', 'footer_shop', 'footer_service'])
            ->unique()
            ->values();
        $cmsPages = CmsPage::query()->active()->ordered()->get(['id', 'title', 'slug']);

        return view('admin.menus.index', compact('menuGroups', 'menuTypes', 'type', 'cmsPages'));
=======
        $menuTypes = Menu::distinct('menu_type')->pluck('menu_type');

        return view('admin.menus.index', compact('menuGroups', 'menuTypes', 'type'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
        $this->authorize('menus.create');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $validated = $request->validate([
            'menu_type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
<<<<<<< HEAD
            'cms_page_id' => 'nullable|exists:cms_pages,id',
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);
<<<<<<< HEAD
        $this->ensureParentMatchesMenuType($validated, $request);
        $validated = $this->prepareMenuData($validated);
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        Menu::create($validated);

        return redirect()->back()->with('success', 'Menu item created successfully!');
    }

    public function edit(Menu $menu)
    {
<<<<<<< HEAD
        $this->authorize('menus.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return response()->json([
            'menu' => $menu->only([
                'id',
                'menu_type',
                'title',
                'url',
                'parent_id',
                'order',
                'status',
            ]),
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
<<<<<<< HEAD
        $this->authorize('menus.update');

        $oldUrl = $this->normalizeMenuUrl($menu->url);

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $validated = $request->validate([
            'menu_type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
<<<<<<< HEAD
            'cms_page_id' => 'nullable|exists:cms_pages,id',
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);
<<<<<<< HEAD
        $this->ensureParentMatchesMenuType($validated, $request);
        $validated = $this->prepareMenuData($validated);

        $menu->update($validated);
        $this->syncCmsPageForMenuUrlChange($oldUrl, $validated['url'] ?? null);
=======

        $menu->update($validated);
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return redirect()->back()->with('success', 'Menu item updated successfully!');
    }

    public function destroy(Menu $menu)
    {
<<<<<<< HEAD
        $this->authorize('menus.delete');

        if ($menu->is_permanent) {
            return redirect()->back()->with('error', 'Permanent menu items cannot be deleted.');
        }

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $menu->delete();

        return redirect()->back()->with('success', 'Menu item moved to Recycle Bin successfully.');
    }
<<<<<<< HEAD

    private function ensureParentMatchesMenuType(array $validated, Request $request): void
    {
        if (blank($validated['parent_id'] ?? null)) {
            return;
        }

        $parent = Menu::query()->find($validated['parent_id']);

        if (! $parent || $parent->menu_type !== $validated['menu_type']) {
            throw ValidationException::withMessages([
                'parent_id' => 'Parent menu must belong to the same menu type.',
            ]);
        }
    }

    private function prepareMenuData(array $validated): array
    {
        $cmsPageId = $validated['cms_page_id'] ?? null;
        unset($validated['cms_page_id']);

        if ($cmsPageId) {
            $page = CmsPage::query()->findOrFail($cmsPageId);
            $validated['url'] = '/' . ltrim($page->slug, '/');

            return $validated;
        }

        $validated['url'] = $this->normalizeMenuUrl($validated['url'] ?? null);

        if (($validated['menu_type'] ?? null) === 'admin_sidebar' && Str::startsWith((string) $validated['url'], '/admin')) {
            $adminPath = trim((string) env('ADMIN_PATH', 'panel'), '/') ?: 'panel';
            $validated['url'] = preg_replace('#^/admin(?=/|$)#', '/' . $adminPath, (string) $validated['url']);
        }

        return $validated;
    }

    private function normalizeMenuUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://', '#'])) {
            return $url;
        }

        $segments = collect(explode('/', trim($url, '/')))
            ->filter(fn ($segment) => $segment !== '')
            ->map(fn ($segment) => Str::slug($segment))
            ->filter(fn ($segment) => $segment !== '')
            ->values();

        return $segments->isEmpty() ? '/' : '/' . $segments->implode('/');
    }

    private function syncCmsPageForMenuUrlChange(?string $oldUrl, ?string $newUrl): void
    {
        if (! $this->isLocalPageUrl($oldUrl) || ! $this->isLocalPageUrl($newUrl) || $oldUrl === $newUrl) {
            return;
        }

        $oldSlug = trim((string) $oldUrl, '/');
        $newSlug = trim((string) $newUrl, '/');

        if ($oldSlug === '' || $newSlug === '') {
            return;
        }

        $page = CmsPage::query()->where('slug', $oldSlug)->first();

        if (! $page || CmsPage::query()->where('slug', $newSlug)->whereKeyNot($page->id)->exists()) {
            return;
        }

        $page->update(['slug' => $newSlug]);

        Menu::query()
            ->where('url', $oldUrl)
            ->update(['url' => $newUrl]);
    }

    private function isLocalPageUrl(?string $url): bool
    {
        return filled($url) && Str::startsWith($url, '/') && ! Str::startsWith($url, ['//', '/admin', '/panel', '/api', '/storage']);
    }
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
}

