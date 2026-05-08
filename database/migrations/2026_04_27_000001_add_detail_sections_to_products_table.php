<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'technical_specifications')) {
                $table->json('technical_specifications')->nullable()->after('seo_description');
            }

            if (! Schema::hasColumn('products', 'customization_options')) {
                $table->json('customization_options')->nullable()->after('technical_specifications');
            }

            if (! Schema::hasColumn('products', 'faqs')) {
                $table->json('faqs')->nullable()->after('customization_options');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach (['faqs', 'customization_options', 'technical_specifications'] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
