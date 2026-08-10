<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class CreateJobDeletePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create job.delete permission
        $permission = Permission::firstOrCreate([
            'name' => 'job.delete',
            'guard_name' => 'admin',
            'group_name' => 'Shop Management'
        ]);

        // Assign to admin user (ID 1)
        $admin = Admin::find(1);
        if ($admin) {
            $admin->givePermissionTo($permission);
            $this->command->info('job.delete permission created and assigned to admin user');
        } else {
            $this->command->error('Admin user not found');
        }
    }
}
