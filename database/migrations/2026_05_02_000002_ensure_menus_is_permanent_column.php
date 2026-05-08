<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menus')) {
            return;
        }

        if (! Schema::hasColumn('menus', 'is_permanent')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->boolean('is_permanent')->default(false)->after('status');
            });
        }

        DB::table('menus')->update(['is_permanent' => true]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('menus') || ! Schema::hasColumn('menus', 'is_permanent')) {
            return;
        }

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('is_permanent');
        });
    }
};
