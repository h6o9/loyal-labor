<?php

namespace App\Helpers;

use Spatie\Permission\Models\Permission;

class SidebarMenuHelper
{
    /**
     * Get admin sidebar menu structure with permissions based on actual database
     */
    public static function getAdminSidebarMenu()
    {
        return [
            'dashboard' => [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'permissions' => [
                    'dashboard.view' => 'Can View Dashboard'
                ]
            ],
            'admin_settings' => [
                'title' => 'Admin Settings',
                'icon' => 'fas fa-user-shield',
                'permissions' => [
                    'role.view' => 'Manage Roles',
                    'role.create' => 'Create Role',
                    'role.edit' => 'Edit Role',
                    'role.assign' => 'Assign Permissions',
                    'admin.view' => 'View Admins',
                    'admin.create' => 'Add Sub Admin',
                    'admin.edit' => 'Edit Admin',
                    'admin.delete' => 'Delete Admin',
                    'activity.logs.view' => 'Activity Logs',
                    'staff.permission.view' => 'Staff Permissions',
                    'staff.permissions.edit' => 'Edit Staff Permissions'
                ]
            ],
            'staff_management' => [
                'title' => 'Staff Management',
                'icon' => 'fas fa-users',
                'permissions' => [
                    'staff.view' => 'Staff List',
                    'staff.create' => 'Add Staff',
                    'staff.edit' => 'Edit Staff',
                    'staff.delete' => 'Delete Staff',
                    'staff.status' => 'Change Staff Status'
                ]
            ],
            'shop_management' => [
                'title' => 'Shop Management',
                'icon' => 'fas fa-store',
                'permissions' => [
                    'shop-management.view' => 'Shop Management',
                    'shop-management.assign' => 'Assign Shop',
                    'shop-management.view-staff-permissions' => 'View Staff Permissions',
                    'shop.view' => 'Shop List',
                    'shop.create' => 'Add Shop'
                ]
            ],
            'tasks_jobs' => [
                'title' => 'Tasks & Jobs',
                'icon' => 'fas fa-tasks',
                'permissions' => [
                    'task.create' => 'Create Task',
                    'task.view' => 'View Tasks'
                ]
            ],
            'settings' => [
                'title' => 'Settings',
                'icon' => 'fas fa-cog',
                'permissions' => [
                    'setting.view' => 'View Settings',
                    'setting.update' => 'Update Settings',
                    'permission.view' => 'View Permissions'
                ]
            ]
        ];
    }

    /**
     * Get all permissions grouped by menu section from actual database
     */
    public static function getPermissionsByMenu()
    {
        $menuStructure = self::getAdminSidebarMenu();
        $permissionsByMenu = [];

        foreach ($menuStructure as $key => $menu) {
            $permissionsByMenu[$menu['title']] = [];
            
            foreach ($menu['permissions'] as $permissionName => $label) {
                // Check if permission exists in database
                $permission = Permission::where('name', $permissionName)->where('guard_name', 'admin')->first();
                if ($permission) {
                    $permissionsByMenu[$menu['title']][] = [
                        'name' => $permission->name,
                        'label' => $label,
                        'id' => $permission->id
                    ];
                }
            }
        }

        return $permissionsByMenu;
    }

    /**
     * Get all admin permissions for assignment (grouped by category)
     */
    public static function getAllAdminPermissions()
    {
        return Permission::where('guard_name', 'admin')
            ->orderBy('name')
            ->get()
            ->groupBy(function($permission) {
                // Group permissions by their first part before dot
                $parts = explode('.', $permission->name);
                return ucfirst($parts[0]);
            });
    }

    /**
     * Create missing permissions for admin panel
     */
    public static function createMissingPermissions()
    {
        $menuStructure = self::getAdminSidebarMenu();
        $createdPermissions = [];

        foreach ($menuStructure as $key => $menu) {
            foreach ($menu['permissions'] as $permissionName => $label) {
                $permission = Permission::where('name', $permissionName)->where('guard_name', 'admin')->first();
                if (!$permission) {
                    $permission = Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'admin'
                    ]);
                    $createdPermissions[] = $permission->name;
                }
            }
        }

        return $createdPermissions;
    }
}
