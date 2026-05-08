<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menus')) {
            return;
        }

        DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->where('url', 'like', '/admin/cms/%/edit')
            ->delete();
    }

    public function down(): void
    {
        //
    }
};
