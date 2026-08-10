<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create district permissions
        $permissions = [
            ['name' => 'district.view', 'guard_name' => 'admin', 'group_name' => 'district'],
            ['name' => 'staff.assign.district', 'guard_name' => 'admin', 'group_name' => 'staff'],
            ['name' => 'staff.view.district', 'guard_name' => 'admin', 'group_name' => 'staff'],
            ['name' => 'shop.view.district', 'guard_name' => 'admin', 'group_name' => 'shop'],
            ['name' => 'job.assign.district', 'guard_name' => 'admin', 'group_name' => 'job'],
        ];

        foreach ($permissions as $perm) {
            $exists = \DB::table('permissions')->where('name', $perm['name'])->where('guard_name', $perm['guard_name'])->exists();
            if (!$exists) {
                \DB::table('permissions')->insert(array_merge($perm, ['created_at' => now(), 'updated_at' => now()]));
            }
        }

        // Get permission IDs
        $districtViewId = \DB::table('permissions')->where('name', 'district.view')->where('guard_name', 'admin')->value('id');
        $staffAssignDistrictId = \DB::table('permissions')->where('name', 'staff.assign.district')->where('guard_name', 'admin')->value('id');
        $staffViewDistrictId = \DB::table('permissions')->where('name', 'staff.view.district')->where('guard_name', 'admin')->value('id');
        $shopViewDistrictId = \DB::table('permissions')->where('name', 'shop.view.district')->where('guard_name', 'admin')->value('id');
        $jobAssignDistrictId = \DB::table('permissions')->where('name', 'job.assign.district')->where('guard_name', 'admin')->value('id');

        // Assign to admin user (ID 1)
        $adminPermissions = [$districtViewId, $staffAssignDistrictId, $staffViewDistrictId, $shopViewDistrictId, $jobAssignDistrictId];
        foreach ($adminPermissions as $permId) {
            $exists = \DB::table('model_has_permissions')
                ->where('permission_id', $permId)
                ->where('model_type', 'App\Models\Admin')
                ->where('model_id', 1)
                ->exists();
            if (!$exists) {
                \DB::table('model_has_permissions')->insert([
                    'permission_id' => $permId,
                    'model_type' => 'App\Models\Admin',
                    'model_id' => 1
                ]);
            }
        }

        // Assign to admin role (ID 1)
        foreach ($adminPermissions as $permId) {
            $exists = \DB::table('role_has_permissions')
                ->where('permission_id', $permId)
                ->where('role_id', 1)
                ->exists();
            if (!$exists) {
                \DB::table('role_has_permissions')->insert([
                    'permission_id' => $permId,
                    'role_id' => 1
                ]);
            }
        }

        $this->command->info('District permissions seeded successfully.');
    }
}
