<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('menus')) {
            return;
        }

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_type', 50);
            $table->string('title', 255);
            $table->string('url', 500)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('order')->default(0);
$table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('icon', 100)->nullable();
            $table->string('permission', 100)->nullable();
            $table->boolean('is_permanent')->default(false);
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            $table->index(['menu_type', 'status', 'order']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
