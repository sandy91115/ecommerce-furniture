<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menus')) {
            return;
        }

        $now = now();
        $notDeleted = Schema::hasColumn('menus', 'deleted_at') ? ['deleted_at' => null] : [];
        $permanent = Schema::hasColumn('menus', 'is_permanent') ? ['is_permanent' => true] : [];

        $pagesMenuId = DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', '#pages')
            ->value('id');

        if ($pagesMenuId) {
            DB::table('menus')
                ->where('id', $pagesMenuId)
                ->update(array_merge([
                    'parent_id' => null,
                    'title' => 'Pages',
                    'url' => '/admin/cms',
                    'icon' => 'fas fa-file-lines',
                    'order' => 10,
                    'status' => 'active',
                    'permission' => 'cms.view',
                    'updated_at' => $now,
                ], $notDeleted, $permanent));
        } else {
            DB::table('menus')->updateOrInsert(
                ['menu_type' => 'admin_sidebar', 'url' => '/admin/cms'],
                array_merge([
                    'parent_id' => null,
                    'title' => 'Pages',
                    'icon' => 'fas fa-file-lines',
                    'order' => 10,
                    'status' => 'active',
                    'permission' => 'cms.view',
                    'updated_at' => $now,
                    'created_at' => $now,
                ], $notDeleted, $permanent)
            );
        }

        DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('title', 'Pages List')
            ->delete();

        DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', '#pages')
            ->delete();
    }

    public function down(): void
    {
        //
    }
};
