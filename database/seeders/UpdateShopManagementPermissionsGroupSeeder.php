<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UpdateShopManagementPermissionsGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update shop-management permissions to have proper group name
        $permissions = [
            'view shop-management',
            'edit shop-management',
            'assign shop-management'
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)
                ->where('guard_name', 'admin')
                ->first();
            
            if ($permission) {
                // Update the group_name to 'Shop Management'
                DB::table('permissions')
                    ->where('id', $permission->id)
                    ->update(['group_name' => 'Shop Management']);
                
                $this->command->info("Updated group name for: {$permissionName}");
            }
        }

        $this->command->info('Shop management permissions group names updated successfully!');
    }
}
