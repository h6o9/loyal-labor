<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    use RedirectHelperTrait;

    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('admin.view');

        $query = Admin::notSuperAdmin()->latest();

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('status_toggle', function ($admin) {
                    if (!checkAdminHasPermission('admin.edit')) {
                        return '';
                    }

                    $checked = $admin->status == 'active' ? 'checked' : '';

                    return '<input onchange="changeAdminStatus(' . $admin->id . ')" id="status_toggle_' . $admin->id . '" type="checkbox" ' . $checked . ' data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger">';
                })
                ->addColumn('action', function ($admin) {
                    $html = '';

                    if (checkAdminHasPermission('admin.edit')) {
                        $html .= '<a class="btn btn-primary btn-sm" href="' . route('admin.admin.edit', $admin->id) . '"><i class="fa fa-edit"></i></a> ';
                    }

                    if (checkAdminHasPermission('admin.delete')) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . url('admin/admin/' . $admin->id) . '"><i class="fa fa-trash"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['status_toggle', 'action'])
                ->make(true);
        }

        return view('admin.admin-list.admin');
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('admin.create');
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        if (!$roles->count()) {
            $notification = __('No role found! First, create at least one role. Then, create the admin.');
            $notification = ['message' => $notification, 'alert-type' => 'warning'];

            return to_route('admin.role.create')->with($notification);
        }

        return view('admin.admin-list.create_admin', compact('roles'));
    }

    /**
     * @return mixed
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => __('Name is required'),
            'email.required' => __('Email is required'),
            'status.required' => __('Status is required'),
            'email.unique' => __('Email already exist'),
            'role.array' => __('You must select role'),
        ];
        $this->validate($request, $rules, $customMessages);
        $password =12345678;

        $admin = new Admin;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->status = $request->status;
        $admin->password = Hash::make($password);
        $admin->save();
        if ($request->role) {
            $admin->syncRoles($request->role);
        }

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.admin.index');
    }

    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('admin.edit');
        $admin = Admin::notSuperAdmin()->findOrFail($id);
        $roles = Role::where('name', '!=', 'Super Admin')->get();

        return view('admin.admin-list.edit_admin', compact('roles', 'admin'));
    }

    /**
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::notSuperAdmin()->find($id);
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins,email,' . $admin->id,
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => __('Name is required'),
            'email.required' => __('Email is required'),
            'email.unique' => __('Email already exist'),
            'role.array' => __('You must select role'),
        ];
        $this->validate($request, $rules, $customMessages);
        $password=12345678;

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->status = $request->status;
        $admin->password = Hash::make($password);

        $admin->save();
        if ($request->role) {
            $admin->syncRoles($request->role);
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.admin.index');
    }

    /**
     * @return mixed
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('admin.delete');
        $admin = Admin::notSuperAdmin()->findOrFail($id);
        abort_if($admin->id == 1, 403);
        $admin->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.admin.index');
    }

    public function changeStatus($id)
    {
        // #region agent log
        file_put_contents(base_path('debug-8fd336.log'), json_encode([
            'sessionId' => '8fd336',
            'runId' => 'pre-fix',
            'hypothesisId' => 'F',
            'location' => 'AdminController@changeStatus:entry',
            'message' => 'admin changeStatus hit',
            'data' => ['id' => $id, 'adminExists' => (bool) Admin::notSuperAdmin()->find($id)],
            'timestamp' => (int) (microtime(true) * 1000),
        ])."\n", FILE_APPEND);
        // #endregion

        $admin = Admin::notSuperAdmin()->find($id);
        if (!$admin) {
            // #region agent log
            file_put_contents(base_path('debug-8fd336.log'), json_encode([
                'sessionId' => '8fd336',
                'runId' => 'pre-fix',
                'hypothesisId' => 'F',
                'location' => 'AdminController@changeStatus:null',
                'message' => 'admin not found via notSuperAdmin scope',
                'data' => ['id' => $id],
                'timestamp' => (int) (microtime(true) * 1000),
            ])."\n", FILE_APPEND);
            // #endregion

            return response()->json([
                'success' => false,
                'message' => 'Admin not found',
            ], 404);
        }

        $status = $admin->status == 'active' ? 'inactive' : 'active';
        $admin->status = $status;
        $admin->save();
        $notification = __('Updated Successfully');

        // #region agent log
        file_put_contents(base_path('debug-8fd336.log'), json_encode([
            'sessionId' => '8fd336',
            'runId' => 'pre-fix',
            'hypothesisId' => 'F',
            'location' => 'AdminController@changeStatus:saved',
            'message' => 'admin status saved',
            'data' => ['id' => $id, 'newStatus' => $status],
            'timestamp' => (int) (microtime(true) * 1000),
        ])."\n", FILE_APPEND);
        // #endregion

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully',
        ]);
    }
}
