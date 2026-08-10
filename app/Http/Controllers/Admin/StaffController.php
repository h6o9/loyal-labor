<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Enums\RedirectType;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Yajra\DataTables\Facades\DataTables;


class StaffController extends Controller
{
	use RedirectHelperTrait;

    //
    public function index(Request $request)
    {
        $query = \App\Models\Staff::with('district')->latest();

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            checkAdminHasPermissionAndThrowException('staff.view');

            return DataTables::of($query)
                ->addColumn('district_name', function ($staff) {
                    if ($staff->district) {
                        return '<span class="badge badge-info">' . e($staff->district->name) . '</span>';
                    }

                    return '<span class="text-muted">N/A</span>';
                })
                ->addColumn('status_toggle', function ($staff) {
                    if (!checkAdminHasPermission('staff.edit')) {
                        return '';
                    }

                    $checked = $staff->status == 1 ? 'checked' : '';

                    return '<input type="checkbox" id="status_toggle_' . $staff->id . '" onchange="changeStaffStatus(' . $staff->id . ')" ' . $checked . ' data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger" data-size="small">';
                })
                ->addColumn('action', function ($staff) {
                    $html = '';

                    if (checkAdminHasPermission('staff.edit')) {
                        $html .= '<a class="btn btn-primary btn-sm" href="' . route('admin.staff.edit', $staff->id) . '"><i class="fa fa-edit" aria-hidden="true"></i></a> ';
                    }

                    if (checkAdminHasPermission('staff.delete')) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.staff.destroy', $staff->id) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['district_name', 'status_toggle', 'action'])
                ->make(true);
        }

        $districts = \App\Models\District::all();

        return view('admin.staff.index', compact('districts'));
    }

    public function create()
    {
        $permissions = [
            'add_shop' => 'Add Shop',
            'view_shop_list' => 'Shop List / Jobs'
        ];
        foreach ($permissions as $key => $name) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'staff']);
        }
        $districts = \App\Models\District::where('status', 'active')->get();
        return view('admin.staff.create', compact('permissions', 'districts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|string|max:20',
            'status' => 'required|boolean',
            'district_id' => 'required|exists:districts,id'
        ]);

        $password = 12345678;
        $staff = \App\Models\Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'district_id' => $request->district_id,
            'password' => bcrypt($password),
        ]);

        if ($request->has('permissions')) {
            $staff->syncPermissions($request->permissions);
        }

       
                return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.staff.index');

    }
    public function edit($id)
    {
        $staff = \App\Models\Staff::findOrFail($id);
        $permissions = [
            'add_shop' => 'Add Shop',
            'view_shop_list' => 'Shop List / Jobs'
        ];
        foreach ($permissions as $key => $name) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'staff']);
        }
        $staffPermissions = $staff->permissions->pluck('name')->toArray();
        $districts = \App\Models\District::where('status', 'active')->get();;
        return view('admin.staff.edit', compact('staff', 'permissions', 'staffPermissions', 'districts'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'phone' => 'required|string|max:20',
            'status' => 'required|boolean',
            'district_id' => 'required|exists:districts,id'
        ]);

        $password = 12345678;
        $staff = \App\Models\Staff::findOrFail($id);
        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'district_id' => $request->district_id,
            'password' => bcrypt($password),
        ]);

       
        // if ($request->has('permissions')) {
        //     $staff->syncPermissions($request->permissions);
        // }
        // else {
        //     $staff->syncPermissions([]);
        // }
                return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.staff.index');

    }
	
    /**
 * Remove the specified staff from storage.
 */
public function destroy($id)
{
    $staff = \App\Models\Staff::findOrFail($id);
    
    // Don't allow deleting yourself
    // if ($staff->id == auth('admin')->id()) {
    //     return redirect()->back()->with('error', 'You cannot delete your own account!');
    // }

    // 🔥 Delete related data first - Proper order
    try {
        // Delete custom module permissions (HasMany relationship)
        $staff->staffPermissions()->delete();
        
        // Delete assigned jobs (HasMany relationship)
        $staff->assignedJobs()->delete();
        
        // Remove Spatie roles/permissions (pivot tables)
        // Some installations end up with a mis-resolved relationship type that throws:
        // "Call to undefined method Illuminate\Database\Eloquent\Relations\HasMany::detach()"
        // Deleting from pivots directly is safe and avoids relation-method mismatch.
        DB::table('model_has_roles')
            ->where('model_type', Staff::class)
            ->where('model_id', $staff->id)
            ->delete();

        DB::table('model_has_permissions')
            ->where('model_type', Staff::class)
            ->where('model_id', $staff->id)
            ->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        
        // Then delete staff
        $staff->delete();
        
    return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.staff.index');

            
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error deleting staff: ' . $e->getMessage());
    }
}

    public function changeStatus($id)
    {
        // #region agent log
        file_put_contents(base_path('debug-8fd336.log'), json_encode([
            'sessionId' => '8fd336',
            'runId' => 'pre-fix',
            'hypothesisId' => 'STAFF',
            'location' => 'StaffController@changeStatus:entry',
            'message' => 'staff changeStatus hit',
            'data' => ['id' => $id, 'status' => request('status')],
            'timestamp' => (int) (microtime(true) * 1000),
        ])."\n", FILE_APPEND);
        // #endregion

        $staff = Staff::findOrFail($id);
        $staff->status = (int) request('status');
        $staff->save();

        // #region agent log
        file_put_contents(base_path('debug-8fd336.log'), json_encode([
            'sessionId' => '8fd336',
            'runId' => 'pre-fix',
            'hypothesisId' => 'STAFF',
            'location' => 'StaffController@changeStatus:saved',
            'message' => 'staff status saved',
            'data' => ['id' => $id, 'newStatus' => $staff->status],
            'timestamp' => (int) (microtime(true) * 1000),
        ])."\n", FILE_APPEND);
        // #endregion

        return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }

}
