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

        $hasBaseFilename = Schema::hasColumn('product_images', 'base_filename');
        $hasOriginalName = Schema::hasColumn('product_images', 'original_name');
        $hasTempPath = Schema::hasColumn('product_images', 'temp_path');
        $hasAlt = Schema::hasColumn('product_images', 'alt');
        $hasFeatured = Schema::hasColumn('product_images', 'featured');

        Schema::table('product_images', function (Blueprint $table) use ($hasBaseFilename, $hasOriginalName, $hasTempPath, $hasAlt, $hasFeatured) {
            if (! $hasBaseFilename) {
                $table->string('base_filename')->nullable()->after('path');
            }

            if (! $hasOriginalName) {
                $table->string('original_name')->nullable()->after('base_filename');
            }

            if (! $hasTempPath) {
                $table->string('temp_path')->nullable()->after('original_name');
            }

            if (! $hasAlt) {
                $table->string('alt')->nullable()->after('original_name');
            }

            if (! $hasFeatured) {
                $table->boolean('featured')->default(false)->after('alt');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_images')) {
            return;
        }

        $columns = collect(['base_filename', 'original_name', 'temp_path'])
            ->filter(fn (string $column) => Schema::hasColumn('product_images', $column))
            ->values()
            ->all();

        Schema::table('product_images', function (Blueprint $table) use ($columns) {
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
