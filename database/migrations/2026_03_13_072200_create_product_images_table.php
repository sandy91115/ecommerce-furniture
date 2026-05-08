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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->string('base_filename')->nullable();
            $table->string('original_name')->nullable();
            $table->string('alt')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->index(['product_id']);
            $table->index(['product_id', 'featured']);
            $table->index(['product_id', 'base_filename']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};

