<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class AssignRoleController extends Controller
{
    /**
     * Display the assign roles page.
     */
    public function index(Request $request)
    {
        // Check if admin has permission to assign roles
        if (!auth('admin')->user()->can('role.assign')) {
            abort(403, 'You do not have permission to assign roles.');
        }

        $admins = Admin::where('is_super_admin', 0)->get(); // Get sub-admins only
        $roles = Role::where('name', '!=', 'Super Admin')->where('guard_name', 'admin')->get();

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            $query = Admin::where('is_super_admin', 0)->with('roles');

            return DataTables::of($query)
                ->addColumn('roles_badge', function ($admin) {
                    if ($admin->roles->isEmpty()) {
                        return '<span class="text-muted">' . __('No roles assigned') . '</span>';
                    }

                    $html = '';
                    foreach ($admin->roles as $role) {
                        $html .= '<span class="badge badge-primary">' . ucfirst($role->name) . '</span> ';
                    }

                    return $html;
                })
                ->addColumn('action', function ($admin) {
                    return '<button type="button" class="btn btn-sm btn-info edit-admin-roles" data-admin-id="'
                        . $admin->id . '" data-admin-name="' . e($admin->name) . '">'
                        . '<i class="fas fa-edit"></i> ' . __('Edit Roles') . '</button>';
                })
                ->rawColumns(['roles_badge', 'action'])
                ->make(true);
        }

        return view('admin.assign-roles.index', compact('admins', 'roles'));
    }

    /**
     * Assign role to admin.
     */
    public function assign(Request $request)
    {
        // Check if admin has permission to assign roles
        if (!auth('admin')->user()->can('role.assign')) {
            abort(403, 'You do not have permission to assign roles.');
        }

        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        $role = Role::findOrFail($request->role_id);

        // Remove all existing roles and assign new one
        $admin->syncRoles([$role]);

        return redirect()->route('admin.assign-roles.index')
            ->with('success', 'Role assigned successfully');
    }

    /**
     * Get admin roles (AJAX endpoint).
     */
    public function getAdminRoles(Admin $admin)
    {
        // Check if admin has permission to assign roles
        if (!auth('admin')->user()->can('role.assign')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $role = $admin->roles->first();
        $roleId = $role ? $role->id : null;

        return response()->json([$roleId]);
    }
}
