<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            if (! Schema::hasColumn('product_images', 'title')) {
                $table->string('title')->nullable()->after('alt');
            }

            if (! Schema::hasColumn('product_images', 'caption')) {
                $table->string('caption')->nullable()->after('title');
            }

            if (! Schema::hasColumn('product_images', 'description')) {
                $table->text('description')->nullable()->after('caption');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $drop = array_values(array_filter(['title', 'caption', 'description'], fn ($column) => Schema::hasColumn('product_images', $column)));

            if ($drop) {
                $table->dropColumn($drop);
            }
        });
    }
};
