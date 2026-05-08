<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (! Schema::hasColumn('reviews', 'reviewer_name')) {
                $table->string('reviewer_name')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('reviews', 'reviewer_email')) {
                $table->string('reviewer_email')->nullable()->after('reviewer_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            foreach (['reviewer_email', 'reviewer_name'] as $column) {
                if (Schema::hasColumn('reviews', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
