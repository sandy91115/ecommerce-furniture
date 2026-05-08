<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\CmsPage;
use App\Models\Menu;
use App\Models\SeoMetadata;
use App\Services\Seo\SeoMetadataService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

class CMSController extends Controller
{
    public function __construct(
        private \App\Services\CmsService $cmsService
    ) {}

    public function index()
    {
<<<<<<< HEAD
        $this->authorize('cms.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $pages = $this->cmsService->getAllPages();
        return view('admin.cms.index', compact('pages'));
    }

    public function create()
    {
<<<<<<< HEAD
        $this->authorize('cms.create');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('admin.cms.create');
    }

    public function store(\App\Http\Requests\Admin\CmsStoreRequest $request)
    {
<<<<<<< HEAD
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
=======
        $this->cmsService->createPage($request->validated());
        return redirect()->route('admin.cms.index')->with('success', 'CMS Page created successfully.');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function show($id)
    {
<<<<<<< HEAD
        $this->authorize('cms.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $page = $this->cmsService->findPage($id);
        return view('admin.cms.show', compact('page'));
    }

    public function edit($id)
    {
<<<<<<< HEAD
        $this->authorize('cms.update');

        $page = $this->cmsService->findPage($id);
        $seoMetadata = SeoMetadata::query()
            ->where('seoable_type', CmsPage::class)
            ->where('seoable_id', $page->id)
            ->first();

        return view('admin.cms.edit', compact('page', 'seoMetadata'));
=======
        $page = $this->cmsService->findPage($id);
        return view('admin.cms.edit', compact('page'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function update(\App\Http\Requests\Admin\CmsUpdateRequest $request, $id)
    {
<<<<<<< HEAD
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
=======
        $this->cmsService->updatePage($id, $request->validated());
        return redirect()->route('admin.cms.index')->with('success', 'CMS Page updated successfully.');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function destroy($id)
    {
        $this->authorize('cms.delete');
        $this->cmsService->deletePage($id);
<<<<<<< HEAD
        return redirect()->route('admin.cms.index')->with('success', 'Page moved to Recycle Bin successfully.');
    }

    private function isFixedPage(CmsPage $page, array $titleSlugs): bool
    {
        return in_array(Str::slug($page->title), $titleSlugs, true);
=======
        return redirect()->route('admin.cms.index')->with('success', 'CMS Page moved to Recycle Bin successfully.');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }
}
