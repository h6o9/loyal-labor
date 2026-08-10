<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$permission = Permission::firstOrCreate(['name' => 'staff.view', 'guard_name' => 'admin', 'group_name' => 'Staff']);
$role = Role::where('name', 'Super Admin')->where('guard_name', 'admin')->first();
if ($role) {
    $role->givePermissionTo($permission);
    echo "Permission added to Super Admin successfully!\n";
} else {
    echo "Super Admin role not found.\n";
}
