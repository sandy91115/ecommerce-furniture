<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blog_categories')) {
            Schema::table('blog_categories', function (Blueprint $table) {
                if (! Schema::hasColumn('blog_categories', 'name')) {
                    $table->string('name')->nullable()->after('id');
                }

                if (! Schema::hasColumn('blog_categories', 'slug')) {
                    $table->string('slug')->nullable()->unique()->after('name');
                }

                if (! Schema::hasColumn('blog_categories', 'description')) {
                    $table->text('description')->nullable()->after('slug');
                }
            });
        }

        if (! Schema::hasTable('menus')) {
            return;
        }

        $timestamp = now();
        $baseMenuData = [
            'status' => 'active',
            'updated_at' => $timestamp,
            'created_at' => $timestamp,
        ];

        if (Schema::hasColumn('menus', 'is_permanent')) {
            $baseMenuData['is_permanent'] = true;
        }

        $blogMenuId = DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', '/admin/blogs')
            ->value('id');

        if (! $blogMenuId) {
            DB::table('menus')->updateOrInsert(
                [
                    'menu_type' => 'admin_sidebar',
                    'url' => '/admin/blogs',
                ],
                array_merge($baseMenuData, [
                    'parent_id' => null,
                    'title' => 'Blogs',
                    'icon' => 'fas fa-blog',
                    'order' => 80,
                    'permission' => 'blogs.view',
                ])
            );

            $blogMenuId = DB::table('menus')
                ->where('menu_type', 'admin_sidebar')
                ->where('url', '/admin/blogs')
                ->value('id');
        }

        DB::table('menus')->updateOrInsert(
            [
                'menu_type' => 'admin_sidebar',
                'url' => '/admin/blog-categories',
            ],
            array_merge($baseMenuData, [
                'parent_id' => $blogMenuId,
                'title' => 'Blog Categories',
                'icon' => 'fas fa-tags',
                'order' => 81,
                'permission' => 'blogs.view',
            ])
        );
    }

    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            DB::table('menus')
                ->where('menu_type', 'admin_sidebar')
                ->where('url', '/admin/blog-categories')
                ->delete();
        }
    }
};
