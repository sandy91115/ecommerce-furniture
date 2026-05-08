<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        if (Schema::hasTable('cms_pages')) {
            $aboutContent = [
                'title' => 'About Us',
                'story_title' => 'Our Story Journey',
                'story_paragraphs' => [
                    'We are a design-focused manufacturing company specializing in custom solid wood and epoxy resin furniture, Our collection is meticulously crafted to embody elegance and sustainability.',
                    'Every piece we create is handcrafted using carefully sourced solid hardwoods and high-grade epoxy resins, ensuring durability, precision, and timeless appeal.',
                ],
                'story_highlights' => [
                    ['title' => 'Design Solutions', 'icon' => 'design'],
                    ['title' => 'Expertise', 'icon' => 'expertise'],
                    ['title' => 'Sustainable', 'icon' => 'sustainable'],
                    ['title' => 'Quality Assurance', 'icon' => 'quality'],
                    ['title' => 'Collaborative Approach', 'icon' => 'collaborative'],
                ],
                'profiles' => [
                    [
                        'img' => 'assets/img/about/16.png',
                        'name' => 'Prabhat Srivastava',
                        'title' => 'Founder & Director',
                        'desc' => 'With three decades of mastery in the timber industry, Prabhat Srivastava leads Carom Studios with a singular vision: to elevate Indian manufacturing to global excellence. His expertise in materials and production drives our uncompromising commitment to quality and craftsmanship.',
                    ],
                    [
                        'img' => 'assets/img/about/15.png',
                        'name' => 'Rishab Srivastava',
                        'title' => 'Co-Founder & Creative Director',
                        'desc' => 'Rishab Srivastava brings creative vision to Carom Studios. As our Creative Director, he is the driving force behind every piece of furniture we create, translating his passion for design into distinctive, functional pieces.',
                    ],
                ],
                'what_we_do_title' => 'What We Do',
                'what_we_do_description' => 'Discover our premium furniture collections crafted with solid wood and epoxy resin for timeless elegance and durability.',
                'what_we_do' => [
                    ['img' => 'assets/img/what/15.png', 'title' => 'Dinning Tables', 'desc' => 'Luxury solid wood dining tables for memorable gatherings'],
                    ['img' => 'assets/img/what/16.png', 'title' => 'Center Tables', 'desc' => 'Elegant center pieces that elevate any living space'],
                    ['img' => 'assets/img/what/17.png', 'title' => 'Doors', 'desc' => 'Premium solid wood doors with exquisite craftsmanship'],
                    ['img' => 'assets/img/what/18.png', 'title' => 'T.V. Unit & Panels', 'desc' => 'Modern TV units and decorative panels for sophistication'],
                ],
                'how_we_do_title' => 'How We Do',
                'how_we_do' => [
                    ['img' => 'assets/img/what/19.png', 'title' => 'Material Selection', 'desc' => 'Premium solid woods and high-grade epoxy resins carefully sourced'],
                    ['img' => 'assets/img/what/20.png', 'title' => 'In-House Manufacturing', 'desc' => 'Precision craftsmanship in our dedicated workshops'],
                    ['img' => 'assets/img/what/21.png', 'title' => 'Hand Finishing & Polishing', 'desc' => 'Artisan hand-finishing for flawless perfection'],
                    ['img' => 'assets/img/what/22.png', 'title' => 'Quality Check', 'desc' => 'Rigorous inspection ensuring excellence in every piece'],
                ],
                'video_bg' => 'assets/img/about/video-bg.jpg',
                'video_url' => 'https://vimeo.com/360496931',
            ];

            $aboutPage = DB::table('cms_pages')->where('slug', 'about')->first();
            $aboutIsJson = $aboutPage && is_array(json_decode((string) $aboutPage->content, true));

            if (! $aboutIsJson) {
                DB::table('cms_pages')->updateOrInsert(
                    ['slug' => 'about'],
                    [
                        'title' => 'About Us',
                        'content' => json_encode($aboutContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                        'status' => 'active',
                        'meta_title' => 'About Us | Carom Studios',
                        'meta_description' => 'Learn about Carom Studios and our approach to handcrafted furniture.',
                        'sort_order' => 1,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }

        if (! Schema::hasTable('menus') || ! Schema::hasTable('cms_pages')) {
            return;
        }

        $permanent = Schema::hasColumn('menus', 'is_permanent') ? ['is_permanent' => true] : [];
        $notDeleted = Schema::hasColumn('menus', 'deleted_at') ? ['deleted_at' => null] : [];

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
            ], $permanent, $notDeleted)
        );

        DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', '#pages')
            ->delete();

    }

    public function down(): void
    {
        if (! Schema::hasTable('menus')) {
            return;
        }

        DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', 'like', '/admin/cms/%/edit')
            ->delete();
    }
};
