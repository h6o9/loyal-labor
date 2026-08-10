<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class CleanupOldPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove old shop-management permissions
        $oldPermissions = [
            'view shop-management',
            'edit shop-management', 
            'assign shop-management'
        ];

        foreach ($oldPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)
                ->where('guard_name', 'admin')
                ->first();
            
            if ($permission) {
                // Remove from role_has_permissions
                DB::table('role_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->delete();
                
                // Remove from model_has_permissions
                DB::table('model_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->delete();
                
                // Delete the permission
                $permission->delete();
                
                $this->command->info("Removed old permission: {$permissionName}");
            }
        }

        $this->command->info('Cleanup completed successfully!');
    }
}
