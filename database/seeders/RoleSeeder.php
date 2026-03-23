<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['admin', 'customer'] as $roleName) {
            $role = Role::withTrashed()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            if ($role->trashed()) {
                $role->restore();
            }
        }
    }
}
