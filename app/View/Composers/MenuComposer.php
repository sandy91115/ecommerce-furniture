<?php

namespace App\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $menuTypes = [
            'header_main',
            'footer_sitemap',
            'footer_others', 
            'footer_shop',
            'footer_service',
        ];

        $menus = [];
        foreach ($menuTypes as $type) {
            $menus[$type] = Menu::getMenus($type);
        }

        $view->with('menus', $menus);
    }
}
