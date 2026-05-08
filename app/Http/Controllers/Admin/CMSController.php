<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Menu;
use App\Models\SeoMetadata;
use App\Services\Seo\SeoMetadataService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CMSController extends Controller
{
    public function __construct(
        private \App\Services\CmsService $cmsService
    ) {}

    public function index()
    {
        $this->authorize('cms.view');

        $pages = $this->cmsService->getAllPages();
        return view('admin.cms.index', compact('pages'));
    }

    public function create()
    {
        $this->authorize('cms.create');

        return view('admin.cms.create');
    }

    public function store(\App\Http\Requests\Admin\CmsStoreRequest $request)
    {
        $this->authorize('cms.create');

        $data = $request->validated();
        $seoMeta = $data['seo_meta'] ?? [];
        unset($data['seo_meta']);

        $page = $this->cmsService->createPage($data);
        app(SeoMetadataService::class)->syncForModel($page, $seoMeta, [
            'title' => $page->meta_title,
            'description' => $page->meta_description,
            'schema_type' => 'WebPage',
        ]);

        return redirect()->route('admin.cms.index')->with('success', 'Page created successfully.');
    }

    public function show($id)
    {
        $this->authorize('cms.view');

        $page = $this->cmsService->findPage($id);
        return view('admin.cms.show', compact('page'));
    }

    public function edit($id)
    {
        $this->authorize('cms.update');

        $page = $this->cmsService->findPage($id);
        $seoMetadata = SeoMetadata::query()
            ->where('seoable_type', CmsPage::class)
            ->where('seoable_id', $page->id)
            ->first();

        return view('admin.cms.edit', compact('page', 'seoMetadata'));
    }

    public function update(\App\Http\Requests\Admin\CmsUpdateRequest $request, $id)
    {
        $this->authorize('cms.update');

        $data = $request->validated();
        $seoMeta = $data['seo_meta'] ?? [];
        unset($data['seo_meta'], $data['about_images'], $data['page_images']);
        $data = $this->storeAboutImages($request, $id, $data);
        $data = $this->storeContactImage($request, $id, $data);

        $oldSlug = $this->cmsService->findPage($id)->slug;
        $page = $this->cmsService->updatePage($id, $data);
        $this->syncMenuUrlsForSlugChange($oldSlug, $page->slug);
        app(SeoMetadataService::class)->syncForModel($page, $seoMeta, [
            'title' => $page->meta_title,
            'description' => $page->meta_description,
            'schema_type' => 'WebPage',
        ]);

        return redirect()->route('admin.cms.index')->with('success', 'Page updated successfully.');
    }

    private function syncMenuUrlsForSlugChange(string $oldSlug, string $newSlug): void
    {
        if ($oldSlug === $newSlug) {
            return;
        }

        Menu::query()
            ->where('url', '/' . ltrim($oldSlug, '/'))
            ->update(['url' => '/' . ltrim($newSlug, '/')]);
    }

    private function storeAboutImages(\App\Http\Requests\Admin\CmsUpdateRequest $request, $id, array $data): array
    {
        $page = $this->cmsService->findPage($id);

        if (! $this->isFixedPage($page, ['about', 'about-us'])) {
            return $data;
        }

        $content = json_decode($data['content'] ?? '', true);

        if (! is_array($content)) {
            return $data;
        }

        foreach (['profiles', 'what_we_do', 'how_we_do'] as $group) {
            foreach ((array) $request->file("about_images.{$group}", []) as $index => $files) {
                $file = $files['img'] ?? null;

                if ($file instanceof UploadedFile) {
                    $content[$group][$index]['img'] = $file->store("cms/about/{$group}", 'public');
                }
            }
        }

        $videoBg = $request->file('about_images.video_bg');

        if ($videoBg instanceof UploadedFile) {
            $content['video_bg'] = $videoBg->store('cms/about/video', 'public');
        }

        $data['content'] = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return $data;
    }

    private function storeContactImage(\App\Http\Requests\Admin\CmsUpdateRequest $request, $id, array $data): array
    {
        $page = $this->cmsService->findPage($id);

        if (! $this->isFixedPage($page, ['contact', 'contact-us'])) {
            return $data;
        }

        $content = json_decode($data['content'] ?? '', true);

        if (! is_array($content)) {
            $content = [
                'content' => $data['content'] ?? '',
                'image' => 'assets/img/thumb/contact-thumb.jpg',
            ];
        }

        $contactImage = $request->file('page_images.contact_image');

        if ($contactImage instanceof UploadedFile) {
            $content['image'] = $contactImage->store('cms/contact', 'public');
        }

        $data['content'] = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return $data;
    }

    public function destroy($id)
    {
        $this->authorize('cms.delete');
        $this->cmsService->deletePage($id);
        return redirect()->route('admin.cms.index')->with('success', 'Page moved to Recycle Bin successfully.');
    }

    private function isFixedPage(CmsPage $page, array $titleSlugs): bool
    {
        return in_array(Str::slug($page->title), $titleSlugs, true);
    }
}
