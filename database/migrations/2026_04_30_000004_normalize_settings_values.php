<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')
            ->orderBy('id')
            ->get(['id', 'value'])
            ->each(function ($setting) {
                if (! is_string($setting->value)) {
                    return;
                }

                $normalized = $setting->value;

                for ($i = 0; $i < 5; $i++) {
                    $decoded = json_decode($normalized, true);

                    if (json_last_error() !== JSON_ERROR_NONE || ! is_string($decoded)) {
                        break;
                    }

                    $normalized = $decoded;
                }

                if ($normalized !== $setting->value) {
                    DB::table('settings')
                        ->where('id', $setting->id)
                        ->update(['value' => $normalized]);
                }
            });
    }

    public function down(): void
    {
        //
    }
};
