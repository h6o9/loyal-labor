<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Dashboard Permissions
        Permission::firstOrCreate(['name' => 'dashboard.view', 'guard_name' => 'admin']);

        // Admin Settings Permissions
        Permission::firstOrCreate(['name' => 'role.view', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'role.create', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'role.edit', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'role.delete', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'permission.assign', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'admin.activity.view', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'setting.view', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'setting.edit', 'guard_name' => 'admin']);

        // Staff Management Permissions
        Permission::firstOrCreate(['name' => 'staff.view', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'staff.create', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'staff.edit', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'staff.delete', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'staff.permission.view', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'staff.activity.view', 'guard_name' => 'admin']);

        // Shop Management Permissions
        Permission::firstOrCreate(['name' => 'shop.view', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'shop.create', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'shop.edit', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'shop.delete', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'shop.assign', 'guard_name' => 'admin']);

        // Task Management Permissions
        Permission::firstOrCreate(['name' => 'task.view', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'task.create', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'task.edit', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'task.delete', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'task.assign', 'guard_name' => 'admin']);

        $this->command->info('Admin permissions created successfully!');
    }
}
