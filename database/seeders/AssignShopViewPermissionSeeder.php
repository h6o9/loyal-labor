<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class AssignShopViewPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure shop.view permission exists
        $permission = Permission::firstOrCreate([
            'name' => 'shop.view',
            'guard_name' => 'admin'
        ]);

        // Assign to admin user (ID 1)
        $admin = Admin::find(1);
        if ($admin) {
            $admin->givePermissionTo($permission);
            $this->command->info('shop.view permission assigned to admin user');
        } else {
            $this->command->error('Admin user not found');
        }
    }
}
