<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Spatie\Permission\Models\Permission;

class AssignAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user (ID 1)
        $admin = Admin::find(1);
        
        if (!$admin) {
            $this->command->error('Admin user not found!');
            return;
        }

        // Get or create the permissions
        $permissions = [
            'view shop-management',
            'edit shop-management', 
            'assign shop-management'
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'admin']);
            $admin->givePermissionTo($permission);
        }

        $this->command->info('Shop-management permissions assigned to admin user successfully!');
    }
}
