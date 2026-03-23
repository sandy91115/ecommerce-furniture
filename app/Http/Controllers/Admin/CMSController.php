<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CMSController extends Controller
{
    public function __construct(
        private \App\Services\CmsService $cmsService
    ) {}

    public function index()
    {
        $pages = $this->cmsService->getAllPages();
        return view('admin.cms.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.create');
    }

    public function store(\App\Http\Requests\Admin\CmsStoreRequest $request)
    {
        $this->cmsService->createPage($request->validated());
        return redirect()->route('admin.cms.index')->with('success', 'CMS Page created successfully.');
    }

    public function show($id)
    {
        $page = $this->cmsService->findPage($id);
        return view('admin.cms.show', compact('page'));
    }

    public function edit($id)
    {
        $page = $this->cmsService->findPage($id);
        return view('admin.cms.edit', compact('page'));
    }

    public function update(\App\Http\Requests\Admin\CmsUpdateRequest $request, $id)
    {
        $this->cmsService->updatePage($id, $request->validated());
        return redirect()->route('admin.cms.index')->with('success', 'CMS Page updated successfully.');
    }

    public function destroy($id)
    {
        $this->authorize('cms.delete');
        $this->cmsService->deletePage($id);
        return redirect()->route('admin.cms.index')->with('success', 'CMS Page moved to Recycle Bin successfully.');
    }
}
