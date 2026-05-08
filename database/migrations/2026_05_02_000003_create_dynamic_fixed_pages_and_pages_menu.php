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
            $pages = [
                [
                    'title' => 'About Us',
                    'slug' => 'about',
                    'sort_order' => 1,
                    'content' => <<<'HTML'
<h3>Crafted Furniture, Thoughtfully Made</h3>
<p>Carom Studios creates furniture that brings warmth, craft, and practical comfort into everyday spaces. Our collections are designed for homes that value strong materials, clean silhouettes, and pieces with character.</p>
<p>Use this page from the admin panel to manage your About Us story, process, team values, studio details, and brand messaging.</p>
HTML,
                    'meta_title' => 'About Us | Carom Studios',
                    'meta_description' => 'Learn about Carom Studios and our approach to handcrafted furniture.',
                ],
                [
                    'title' => 'Contact Us',
                    'slug' => 'contact',
                    'sort_order' => 2,
                    'content' => <<<'HTML'
<h3>We would love to hear from you</h3>
<p>Have a question about a product, custom furniture, delivery, or an order? Send us a message and our team will get back to you shortly.</p>
HTML,
                    'meta_title' => 'Contact Us | Carom Studios',
                    'meta_description' => 'Contact Carom Studios for product, order, delivery, and custom furniture enquiries.',
                ],
                [
                    'title' => 'Terms & Conditions',
                    'slug' => 'terms-and-conditions',
                    'sort_order' => 3,
                    'content' => <<<'HTML'
<h3>1. Orders & Payments</h3>
<p>By placing an order with Carom Studios, you agree to provide accurate billing, shipping, and contact information. Payments are processed through secure payment partners.</p>
<h3>2. Shipping & Delivery</h3>
<p>Delivery timelines may vary based on location, product availability, and customisation requirements. We will share updates wherever applicable.</p>
<h3>3. Product Information</h3>
<p>We try to represent product colours, materials, and dimensions accurately. Minor variation may occur due to screen settings, lighting, and handcrafted production.</p>
<h3>4. Updates</h3>
<p>These terms may be updated from time to time. The latest version published on this page will apply.</p>
HTML,
                    'meta_title' => 'Terms & Conditions | Carom Studios',
                    'meta_description' => 'Read the terms and conditions for using Carom Studios and placing furniture orders.',
                ],
                [
                    'title' => 'Return Policy',
                    'slug' => 'return-policy',
                    'sort_order' => 4,
                    'content' => <<<'HTML'
<h3>1. Return Eligibility</h3>
<p>Eligible products may be returned within the stated return window when unused, undamaged, and kept in original packaging. Custom or made-to-order furniture may not be returnable unless defective.</p>
<h3>2. Damaged or Defective Items</h3>
<p>Please report damaged or defective items as soon as possible with clear photos of the product and packaging so our team can help quickly.</p>
<h3>3. Refunds</h3>
<p>Approved refunds are processed after inspection. Processing timelines may vary depending on the original payment method and payment partner.</p>
<h3>4. Support</h3>
<p>For return questions, contact the Carom Studios support team with your order details.</p>
HTML,
                    'meta_title' => 'Return Policy | Carom Studios',
                    'meta_description' => 'Read the Carom Studios return policy for furniture orders, refunds, and damaged items.',
                ],
            ];

            foreach ($pages as $page) {
                DB::table('cms_pages')->updateOrInsert(
                    ['slug' => $page['slug']],
                    [
                        'title' => $page['title'],
                        'content' => $page['content'],
                        'status' => 'active',
                        'meta_title' => $page['meta_title'],
                        'meta_description' => $page['meta_description'],
                        'sort_order' => $page['sort_order'],
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }

        if (! Schema::hasTable('menus')) {
            return;
        }

        $permanent = Schema::hasColumn('menus', 'is_permanent') ? ['is_permanent' => true] : [];
        $notDeleted = Schema::hasColumn('menus', 'deleted_at') ? ['deleted_at' => null] : [];

        $pagesMenuId = DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', '/admin/cms')
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
                ], $permanent, $notDeleted)
            );
        }

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
            ->where('url', '/admin/cms')
            ->update([
                'parent_id' => null,
                'title' => 'CMS',
                'icon' => 'fas fa-file-alt',
                'order' => 10,
                'updated_at' => now(),
            ]);

        DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', '#pages')
            ->delete();
    }
};
