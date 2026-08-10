<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPermission;
use Illuminate\Http\Request;
use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;
use Yajra\DataTables\Facades\DataTables;



class StaffPermissionController extends Controller
{
        use RedirectHelperTrait;

    public function index(Request $request)
    {
        $modules = StaffPermission::$modules;

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            $query = Staff::query()->latest();

            return DataTables::of($query)
                ->addColumn('name_badge', function ($member) {
                    $html = '<strong>' . e($member->name) . '</strong>';

                    if (!$member->is_active) {
                        $html .= ' <span class="badge badge-danger ml-2">Inactive</span>';
                    }

                    return $html;
                })
                ->addColumn('permissions_link', function ($member) {
                    return '<a href="' . route('admin.staff-permissions.show', $member->id) . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> View</a>';
                })
                ->addColumn('status_badge', function ($member) {
                    return $member->is_active
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($member) {
                    return '<a href="' . route('admin.staff-permissions.edit', $member->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>';
                })
                ->rawColumns(['name_badge', 'permissions_link', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.staff-permissions.index', compact('modules'));
    }

    public function edit($id)
    {
        $staff = Staff::with('permissions')->findOrFail($id);
        $modules = StaffPermission::$modules;
        
        return view('admin.staff-permissions.edit', compact('staff', 'modules'));
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        
        foreach (StaffPermission::$modules as $moduleKey => $moduleName) {
            StaffPermission::updateOrCreate(
                [
                    'staff_id' => $staff->id,
                    'module' => $moduleKey,
                ],
                [
                    'can_view' => $request->input("permissions.{$moduleKey}.can_view", false),
                    'can_create' => $request->input("permissions.{$moduleKey}.can_create", false),
                    'can_edit' => $request->input("permissions.{$moduleKey}.can_edit", false),
                    'can_delete' => $request->input("permissions.{$moduleKey}.can_delete", false),
                    'permissable' => $request->input("permissions.{$moduleKey}.permissable", false),
                ]
            );

             
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.staff-permissions.index');
    }

    public function show($id)
    {
        $staff = Staff::with('permissions')->findOrFail($id);
        $modules = StaffPermission::$modules;
        
        return view('admin.staff-permissions.show', compact('staff', 'modules'));
    }
}
