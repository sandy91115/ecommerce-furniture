<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attribute_values')) {
            return;
        }

        if (Schema::hasColumn('attribute_values', 'color_code')) {
            return;
        }

        Schema::table('attribute_values', function (Blueprint $table) {
            $table->string('color_code', 20)->nullable()->after('slug');
            $table->index(['color_code']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('attribute_values') || !Schema::hasColumn('attribute_values', 'color_code')) {
            return;
        }

        Schema::table('attribute_values', function (Blueprint $table) {
            $table->dropIndex(['color_code']);
            $table->dropColumn('color_code');
        });
    }
};

