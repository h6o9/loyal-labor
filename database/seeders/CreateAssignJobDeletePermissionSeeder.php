<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class CreateAssignJobDeletePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create assign.job.delete permission
        $permission = Permission::firstOrCreate([
            'name' => 'assign.job.delete',
            'guard_name' => 'admin',
            'group_name' => 'Shop Management'
        ]);

        // Assign to admin user (ID 1)
        $admin = Admin::find(1);
        if ($admin) {
            $admin->givePermissionTo($permission);
            $this->command->info('assign.job.delete permission created and assigned to admin user');
        } else {
            $this->command->error('Admin user not found');
        }
    }
}
