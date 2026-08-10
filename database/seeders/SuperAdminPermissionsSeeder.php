<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class SuperAdminPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Get Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->where('guard_name', 'admin')->first();
        
        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found!');
            return;
        }

        // Get all admin permissions
        $permissions = Permission::where('guard_name', 'admin')->get();

        // Assign all permissions to Super Admin
        $superAdminRole->syncPermissions($permissions);

        // Get Super Admin user (admin@gmail.com)
        $superAdmin = Admin::where('email', 'admin@gmail.com')->first();
        
        if ($superAdmin) {
            // Make sure user has Super Admin role
            if (!$superAdmin->hasRole('Super Admin')) {
                $superAdmin->assignRole('Super Admin');
            }
            
            // Give all permissions directly to user as backup
            $superAdmin->syncPermissions($permissions);
            
            $this->command->info('Super Admin (admin@gmail.com) has been given all permissions!');
        } else {
            $this->command->error('Super Admin user not found!');
        }

        $this->command->info('Super Admin permissions setup completed!');
    }
}
