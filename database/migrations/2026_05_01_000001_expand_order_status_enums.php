<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','processing','shipped','delivered','cancelled','returned','refunded') NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending'");
        }

        $paidPendingUpdate = ['status' => 'processing'];

        if (Schema::hasColumn('orders', 'updated_at')) {
            $paidPendingUpdate['updated_at'] = now();
        }

        DB::table('orders')
            ->where('status', 'pending')
            ->where('payment_status', 'paid')
            ->update($paidPendingUpdate);
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        DB::table('orders')
            ->whereIn('status', ['returned', 'refunded'])
            ->update(['status' => 'cancelled']);

        DB::table('orders')
            ->where('payment_status', 'refunded')
            ->update(['payment_status' => 'failed']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending'");
        }
    }
};
