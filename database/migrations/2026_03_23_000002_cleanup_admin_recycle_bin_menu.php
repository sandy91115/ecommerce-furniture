<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->whereIn('url', ['/admin/blogs/trash', '/admin/cms/trash'])
            ->delete();

        DB::table('menus')->updateOrInsert(
            [
                'menu_type' => 'admin_sidebar',
                'url' => '/admin/trash',
            ],
            [
                'parent_id' => null,
                'title' => 'Recycle Bin',
                'icon' => 'fas fa-recycle',
                'order' => 999,
                'status' => 'active',
                'permission' => 'admin.access',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', '/admin/trash')
            ->delete();

        $timestamp = now();

        DB::table('menus')->updateOrInsert(
            [
                'menu_type' => 'admin_sidebar',
                'url' => '/admin/blogs/trash',
            ],
            [
                'parent_id' => null,
                'title' => 'Blog Trash',
                'icon' => 'fas fa-trash',
                'order' => 81,
                'status' => 'active',
                'permission' => 'blogs.view',
                'updated_at' => $timestamp,
                'created_at' => $timestamp,
            ]
        );

        DB::table('menus')->updateOrInsert(
            [
                'menu_type' => 'admin_sidebar',
                'url' => '/admin/cms/trash',
            ],
            [
                'parent_id' => null,
                'title' => 'CMS Trash',
                'icon' => 'fas fa-trash',
                'order' => 91,
                'status' => 'active',
                'permission' => 'cms.view',
                'updated_at' => $timestamp,
                'created_at' => $timestamp,
            ]
        );
    }
};
