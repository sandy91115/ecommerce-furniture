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
            DB::table('cms_pages')->updateOrInsert(
                ['slug' => 'privacy-policy'],
                [
                    'title' => 'Privacy Policy',
                    'content' => <<<'HTML'
<h3>1. Information We Collect</h3>
<p>We may collect your name, email address, phone number, delivery address, order details, and messages submitted through our website forms.</p>
<h3>2. How We Use Your Information</h3>
<p>Your information is used to process orders, respond to enquiries, provide customer support, improve our services, and share important updates related to your purchases.</p>
<h3>3. Data Protection</h3>
<p>We take reasonable steps to protect your personal information from unauthorized access, misuse, alteration, or disclosure.</p>
<h3>4. Third-Party Services</h3>
<p>We may use trusted payment, delivery, analytics, and communication partners where required to operate our services.</p>
<h3>5. Contact</h3>
<p>For privacy-related questions, contact Carom Studios through the contact page.</p>
HTML,
                    'status' => 'active',
                    'meta_title' => 'Privacy Policy | Carom Studios',
                    'meta_description' => 'Read the Carom Studios privacy policy and how customer information is used and protected.',
                    'sort_order' => 5,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        if (! Schema::hasTable('menus')) {
            return;
        }

        $permanent = Schema::hasColumn('menus', 'is_permanent') ? ['is_permanent' => true] : [];
        $notDeleted = Schema::hasColumn('menus', 'deleted_at') ? ['deleted_at' => null] : [];

        DB::table('menus')->updateOrInsert(
            ['menu_type' => 'footer_service', 'url' => '/privacy-policy'],
            array_merge([
                'parent_id' => null,
                'title' => 'Privacy Policy',
                'icon' => null,
                'order' => 4,
                'status' => 'active',
                'permission' => null,
                'updated_at' => $now,
                'created_at' => $now,
            ], $permanent, $notDeleted)
        );
    }

    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            DB::table('menus')
                ->where('menu_type', 'footer_service')
                ->where('url', '/privacy-policy')
                ->delete();
        }

        if (Schema::hasTable('cms_pages')) {
            DB::table('cms_pages')
                ->where('slug', 'privacy-policy')
                ->delete();
        }
    }
};
