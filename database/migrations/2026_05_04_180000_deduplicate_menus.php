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

        DB::transaction(function () {
            $menus = DB::table('menus')
                ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
                ->when(Schema::hasColumn('menus', 'is_permanent'), fn ($query) => $query->orderByRaw('CASE WHEN is_permanent = 1 THEN 0 ELSE 1 END'))
                ->orderBy('id')
                ->get();

            $menus
                ->groupBy(fn ($menu) => implode('|', [
                    $menu->menu_type,
                    $menu->parent_id ?: 'root',
                    $menu->url ?: '',
                ]))
                ->each(function ($group) {
                    if ($group->count() <= 1) {
                        return;
                    }

                    $keep = $group->first();
                    $duplicateIds = $group->skip(1)->pluck('id')->values();

                    DB::table('menus')
                        ->whereIn('parent_id', $duplicateIds)
                        ->update(['parent_id' => $keep->id]);

                    DB::table('menus')
                        ->whereIn('id', $duplicateIds)
                        ->delete();
                });
        });
    }

    public function down(): void
    {
        //
    }
};
