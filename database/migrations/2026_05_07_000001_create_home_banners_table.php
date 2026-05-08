<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('eyebrow')->default('Featured Pick');
            $table->string('offer_price')->nullable();
            $table->string('offer_title')->nullable();
            $table->string('season_year', 20)->default('2026');
            $table->string('season_text')->default('Summer');
            $table->string('kicker')->nullable();
            $table->string('title')->default('Modern Collections');
            $table->text('description')->nullable();
            $table->string('button_text')->default('View Product');
            $table->string('button_url')->nullable();
            $table->string('secondary_button_text')->default('Shop Collection');
            $table->string('secondary_button_url')->nullable();
            $table->string('image')->nullable();
            $table->string('theme_color', 20)->default('#E3B505');
            $table->unsignedInteger('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['status', 'order']);
        });

        $this->addAdminMenu();
    }

    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            DB::table('menus')
                ->where('menu_type', 'admin_sidebar')
                ->where('url', '/admin/banners')
                ->delete();
        }

        Schema::dropIfExists('home_banners');
    }

    private function addAdminMenu(): void
    {
        if (! Schema::hasTable('menus')) {
            return;
        }

        $payload = [
            'title' => 'Banners',
            'icon' => 'fa-image',
            'order' => 8,
            'status' => 'active',
            'permission' => 'admin.access',
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('menus', 'is_permanent')) {
            $payload['is_permanent'] = true;
        }

        if (! DB::table('menus')->where('menu_type', 'admin_sidebar')->where('url', '/admin/banners')->exists()) {
            $payload['created_at'] = now();
        }

        DB::table('menus')->updateOrInsert(
            ['menu_type' => 'admin_sidebar', 'url' => '/admin/banners'],
            $payload
        );
    }
};
