<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Services\Seo\SeoManager;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    public function show(CmsPage $page)
    {
        abort_unless($page->status === 'active', 404);

        $seo = app(SeoManager::class)->forCmsPage($page);

        if ($this->isContactPage($page)) {
            return view('contact', compact('page', 'seo'));
        }

        if ($this->isAboutPage($page)) {
            return view('about', compact('page', 'seo'));
        }

        return view('cms-page', compact('page', 'seo'));
    }

    private function isContactPage(CmsPage $page): bool
    {
        return in_array(Str::slug($page->title), ['contact', 'contact-us'], true);
    }

    private function isAboutPage(CmsPage $page): bool
    {
        return in_array(Str::slug($page->title), ['about', 'about-us'], true);
    }
}
