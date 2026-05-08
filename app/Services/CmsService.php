<?php

namespace App\Services;

use App\Models\CmsPage;

class CmsService
{
    public function getAllPages($perPage = 10)
    {
        return CmsPage::ordered()->paginate($perPage);
    }

    public function findPage($id)
    {
        return CmsPage::findOrFail($id);
    }

    public function createPage(array $data)
    {
        return CmsPage::create($data);
    }

    public function updatePage($id, array $data)
    {
        $page = $this->findPage($id);
        $page->update($data);
        return $page->fresh();
    }

    public function deletePage($id)
    {
        $page = $this->findPage($id);
        $page->delete();
        return true;
    }
}

