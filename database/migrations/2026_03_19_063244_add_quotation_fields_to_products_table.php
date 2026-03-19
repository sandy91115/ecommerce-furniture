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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('product_type', ['sell', 'quotation'])->default('sell')->after('status');
            $table->string('extra_title')->nullable()->after('product_type');
            $table->text('extra_description')->nullable()->after('extra_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_type', 'extra_title', 'extra_description']);
        });
    }
};
