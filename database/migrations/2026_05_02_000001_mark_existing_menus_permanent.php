<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menus') || ! Schema::hasColumn('menus', 'is_permanent')) {
            return;
        }

        DB::table('menus')->update(['is_permanent' => true]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('menus') || ! Schema::hasColumn('menus', 'is_permanent')) {
            return;
        }

        DB::table('menus')->update(['is_permanent' => false]);
    }
};
