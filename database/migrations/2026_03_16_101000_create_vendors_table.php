<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('store_name');
            $table->string('store_slug')->unique();
            $table->string('store_logo')->nullable();
            $table->string('store_banner')->nullable();
            $table->text('store_description')->nullable();
            $table->text('store_address')->nullable();
            $table->string('store_phone')->nullable();
            $table->enum('status', ['active', 'pending', 'inactive'])->default('pending');
            $table->timestamps();

            $table->index(['status']);
        });

        // Helpful default for single-vendor installs: create a vendor for the first user if present.
        if (Schema::hasTable('users') && DB::table('users')->where('id', 1)->exists()) {
            DB::table('vendors')->insertOrIgnore([
                'user_id' => 1,
                'store_name' => 'Default Store',
                'store_slug' => 'default-store',
                'store_description' => 'Default vendor store.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};

