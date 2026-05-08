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

        $menus = DB::table('menus')
            ->where('menu_type', 'admin_sidebar')
            ->when(Schema::hasColumn('menus', 'deleted_at'), fn ($query) => $query->whereNull('deleted_at'))
            ->orderBy('order')
            ->orderBy('id')
            ->get(['id']);

        foreach ($menus as $index => $menu) {
            DB::table('menus')
                ->where('id', $menu->id)
                ->update([
                    'order' => $index + 1,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // The previous ordering may have contained duplicates, so it is not safe to reconstruct.
    }
};
