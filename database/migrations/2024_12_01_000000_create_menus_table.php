<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_type'); // header_main, footer_sitemap, footer_shop, footer_service, admin_sidebar
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->string('title');
            $table->string('url')->nullable(); // or slug/path
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['menu_type', 'status', 'parent_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};

