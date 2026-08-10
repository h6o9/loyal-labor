<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminShopManagementPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for shop-management
        $permissions = [
            'view shop-management',
            'edit shop-management',
            'assign shop-management'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Get the admin role or create it if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);

        // Assign all shop-management permissions to admin role
        $adminRole->givePermissionTo($permissions);

        // Also assign to super admin if exists
        $superAdminRole = Role::where('name', 'super-admin')->where('guard_name', 'admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }

        $this->command->info('Admin shop-management permissions seeded successfully!');
    }
}
