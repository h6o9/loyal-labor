<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class CreateShopEditPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create shop.edit permission
        $permission = Permission::firstOrCreate([
            'name' => 'shop.edit',
            'guard_name' => 'admin'
        ]);

        // Assign to admin user (ID 1)
        $admin = Admin::find(1);
        if ($admin) {
            $admin->givePermissionTo($permission);
            $this->command->info('shop.edit permission created and assigned to admin user');
        } else {
            $this->command->error('Admin user not found');
        }
    }
}
