<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'product_rating')) {
                $table->decimal('product_rating', 2, 1)->nullable()->after('faqs');
            }

            if (! Schema::hasColumn('products', 'product_rating_count')) {
                $table->unsignedInteger('product_rating_count')->nullable()->after('product_rating');
            }

            if (! Schema::hasColumn('products', 'care_and_maintenance')) {
                $table->longText('care_and_maintenance')->nullable()->after('product_rating_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach (['care_and_maintenance', 'product_rating_count', 'product_rating'] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
