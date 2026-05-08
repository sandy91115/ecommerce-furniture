<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions (exact as recommended)
        $permissions = [
            // Admin access
            'admin.access',
            'dashboard.view',
            
            // Categories
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            // Catalog & supporting admin modules
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'attributes.view',
            'attributes.create',
            'attributes.update',
            'attributes.delete',
            'coupons.view',
            'coupons.create',
            'coupons.update',
            'coupons.delete',
            'contacts.view',
            'contacts.delete',
            'quotations.view',
            'quotations.update',
            'quotations.delete',
            'reviews.view',
            'reviews.create',
            'reviews.update',
            'reviews.delete',
            'reviews.moderate',
            
            // Orders
            'orders.view',
            'orders.show',
            'orders.update',
            'orders.update_status',
            'orders.delete',
            
            // Customers
            'customers.view',
            'customers.show',
            'customers.update',
            'customers.create',
            'customers.verify',
            
            // Blogs
            'blogs.view',
            'blogs.create',
            'blogs.update',
            'blogs.delete',
            
            // CMS
            'cms.view',
            'cms.create',
            'cms.update',
            'cms.delete',
            
            // Staff
            'staff.view',
            'staff.create',
            'staff.update',
            'staff.delete',
            'staff.assign_roles',
            'staff.assign_permissions',
            
            // Roles & Permissions (super_admin only)
            'roles.view',
            'roles.manage',
            'permissions.manage',
            
            // Menus & Settings
            'menus.view',
            'menus.create',
            'menus.update',
            'menus.delete',
            'settings.view',
            'settings.update',
            'payment_methods.view',
            'payment_methods.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Roles
        $superAdminRole = Role::withTrashed()->firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        if ($superAdminRole->trashed()) {
            $superAdminRole->restore();
        }
        $superAdminRole->syncPermissions($permissions); // All permissions

        $shopTeamRole = Role::withTrashed()->firstOrCreate(['name' => 'shop_team', 'guard_name' => 'web']);
        if ($shopTeamRole->trashed()) {
            $shopTeamRole->restore();
        }
        $shopTeamPerms = [
            'admin.access',
            'dashboard.view',
            'categories.view',
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'attributes.view',
            'attributes.create',
            'attributes.update',
            'attributes.delete',
            'orders.view',
            'orders.show',
            'orders.update',
            'orders.update_status',
            'customers.view',
            'customers.show',
            'customers.create',
            'customers.verify',
            'quotations.view',
            'quotations.update',
            'contacts.view',
        ];
        $shopTeamRole->syncPermissions($shopTeamPerms);

        $marketingTeamRole = Role::withTrashed()->firstOrCreate(['name' => 'marketing_team', 'guard_name' => 'web']);
        if ($marketingTeamRole->trashed()) {
            $marketingTeamRole->restore();
        }
        $marketingTeamPerms = [
            'admin.access',
            'dashboard.view',
            'blogs.view',
            'blogs.create',
            'blogs.update',
            'blogs.delete',
            'cms.view',
            'cms.create',
            'cms.update',
            'cms.delete',
            'contacts.view',
            'reviews.view',
            'reviews.create',
            'reviews.update',
            'reviews.delete',
            'reviews.moderate',
            'menus.view',
            'menus.create',
            'menus.update',
            'menus.delete',
        ];
        $marketingTeamRole->syncPermissions($marketingTeamPerms);

        // Legacy 'admin' role -> map to super_admin perms if needed
        $adminRole = Role::withTrashed()->firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        if ($adminRole->trashed()) {
            $adminRole->restore();
        }
        $adminRole->syncPermissions($permissions);

        // Assign super_admin to existing admin user
        $adminUser = User::where('email', 'admin@caromstudios.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('super_admin');
        }
    }
}

