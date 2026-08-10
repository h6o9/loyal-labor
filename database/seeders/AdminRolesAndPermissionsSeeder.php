<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class AdminRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Dashboard permissions
            ['name' => 'dashboard.view', 'guard_name' => 'admin', 'group_name' => 'Dashboard'],
            
            // Admin Management permissions
            ['name' => 'admin.view', 'guard_name' => 'admin', 'group_name' => 'Admin Management'],
            ['name' => 'admin.create', 'guard_name' => 'admin', 'group_name' => 'Admin Management'],
            ['name' => 'admin.edit', 'guard_name' => 'admin', 'group_name' => 'Admin Management'],
            ['name' => 'admin.delete', 'guard_name' => 'admin', 'group_name' => 'Admin Management'],
            ['name' => 'admin.change-status', 'guard_name' => 'admin', 'group_name' => 'Admin Management'],
            
            // Staff Management permissions
            ['name' => 'staff.view', 'guard_name' => 'admin', 'group_name' => 'Staff Management'],
            ['name' => 'staff.create', 'guard_name' => 'admin', 'group_name' => 'Staff Management'],
            ['name' => 'staff.edit', 'guard_name' => 'admin', 'group_name' => 'Staff Management'],
            ['name' => 'staff.delete', 'guard_name' => 'admin', 'group_name' => 'Staff Management'],
            ['name' => 'staff.change-status', 'guard_name' => 'admin', 'group_name' => 'Staff Management'],
            
            // Staff Permissions Management
            ['name' => 'staff-permissions.view', 'guard_name' => 'admin', 'group_name' => 'Staff Permissions'],
            ['name' => 'staff-permissions.edit', 'guard_name' => 'admin', 'group_name' => 'Staff Permissions'],
            
            // Shop Management permissions
            ['name' => 'shop-management.view', 'guard_name' => 'admin', 'group_name' => 'Shop Management'],
            ['name' => 'shop-management.assign', 'guard_name' => 'admin', 'group_name' => 'Shop Management'],
            ['name' => 'shop-management.view-staff-permissions', 'guard_name' => 'admin', 'group_name' => 'Shop Management'],
            
            // Roles & Permissions permissions
            ['name' => 'roles.view', 'guard_name' => 'admin', 'group_name' => 'Roles & Permissions'],
            ['name' => 'roles.create', 'guard_name' => 'admin', 'group_name' => 'Roles & Permissions'],
            ['name' => 'roles.edit', 'guard_name' => 'admin', 'group_name' => 'Roles & Permissions'],
            ['name' => 'roles.delete', 'guard_name' => 'admin', 'group_name' => 'Roles & Permissions'],
            ['name' => 'roles.assign', 'guard_name' => 'admin', 'group_name' => 'Roles & Permissions'],
            
            // Location Management permissions
            ['name' => 'countries.view', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'countries.create', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'countries.edit', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'countries.delete', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            
            ['name' => 'states.view', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'states.create', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'states.edit', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'states.delete', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            
            ['name' => 'cities.view', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'cities.create', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'cities.edit', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            ['name' => 'cities.delete', 'guard_name' => 'admin', 'group_name' => 'Location Management'],
            
            // Settings permissions
            ['name' => 'settings.view', 'guard_name' => 'admin', 'group_name' => 'Settings'],
            ['name' => 'settings.edit', 'guard_name' => 'admin', 'group_name' => 'Settings'],
            
            // Activity Logs permissions
            ['name' => 'activity-logs.view', 'guard_name' => 'admin', 'group_name' => 'Activity Logs'],
            ['name' => 'activity-logs.view-subadmin', 'guard_name' => 'admin', 'group_name' => 'Activity Logs'],
            
            // Profile permissions
            ['name' => 'profile.view', 'guard_name' => 'admin', 'group_name' => 'Profile'],
            ['name' => 'profile.edit', 'guard_name' => 'admin', 'group_name' => 'Profile'],
            ['name' => 'profile.change-password', 'guard_name' => 'admin', 'group_name' => 'Profile'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name']
            ], [
                'group_name' => $permission['group_name']
            ]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'admin'
        ]);

        $managerRole = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'admin'
        ]);

        $operatorRole = Role::firstOrCreate([
            'name' => 'Operator',
            'guard_name' => 'admin'
        ]);

        // Assign all permissions to Super Admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Assign permissions to Admin role
        $adminPermissions = Permission::whereNotIn('group_name', ['Activity Logs'])->get();
        $adminRole->givePermissionTo($adminPermissions);

        // Assign limited permissions to Manager role
        $managerPermissions = Permission::whereIn('group_name', [
            'Dashboard',
            'Staff Management', 
            'Shop Management',
            'Staff Permissions',
            'Profile'
        ])->get();
        $managerRole->givePermissionTo($managerPermissions);

        // Assign very limited permissions to Operator role
        $operatorPermissions = Permission::whereIn('group_name', [
            'Dashboard',
            'Shop Management',
            'Profile'
        ])->get();
        $operatorRole->givePermissionTo($operatorPermissions);

        // Assign Super Admin role to first admin user if exists
        $firstAdmin = Admin::first();
        if ($firstAdmin) {
            $firstAdmin->assignRole('Super Admin');
        }

        $this->command->info('Admin roles and permissions created successfully!');
    }
}
