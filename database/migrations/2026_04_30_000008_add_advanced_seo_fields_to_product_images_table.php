<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_images')) {
            return;
        }

        Schema::table('product_images', function (Blueprint $table) {
            if (! Schema::hasColumn('product_images', 'seo_filename')) {
                $table->string('seo_filename')->nullable()->after('original_name');
            }

            if (! Schema::hasColumn('product_images', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('featured');
            }

            if (! Schema::hasColumn('product_images', 'width')) {
                $table->unsignedInteger('width')->nullable()->after('sort_order');
            }

            if (! Schema::hasColumn('product_images', 'height')) {
                $table->unsignedInteger('height')->nullable()->after('width');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_images')) {
            return;
        }

        Schema::table('product_images', function (Blueprint $table) {
            $columns = collect(['seo_filename', 'sort_order', 'width', 'height'])
                ->filter(fn (string $column) => Schema::hasColumn('product_images', $column))
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
