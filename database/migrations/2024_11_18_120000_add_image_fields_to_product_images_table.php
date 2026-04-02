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
        if (! Schema::hasTable('product_images')) {
            return;
        }

        $hasBaseFilename = Schema::hasColumn('product_images', 'base_filename');
        $hasOriginalName = Schema::hasColumn('product_images', 'original_name');
        $hasTempPath = Schema::hasColumn('product_images', 'temp_path');

        if ($hasBaseFilename && $hasOriginalName && $hasTempPath) {
            return;
        }

        Schema::table('product_images', function (Blueprint $table) use ($hasBaseFilename, $hasOriginalName, $hasTempPath) {
            if (! $hasBaseFilename) {
                $table->string('base_filename')->nullable()->after('path');
            }

            if (! $hasOriginalName) {
                $table->string('original_name')->nullable()->after('base_filename');
            }

            if (! $hasTempPath) {
                $table->string('temp_path')->nullable()->after('original_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
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

