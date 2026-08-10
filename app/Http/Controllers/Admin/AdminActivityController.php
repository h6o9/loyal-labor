<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminActivityController extends Controller
{
    /**
     * Display a listing of the admin activities.
     */
    public function index(Request $request)
    {
        // Check if admin has permission to view activity logs
        if (!auth('admin')->user()->can('activity.logs.view')) {
            abort(403, 'You do not have permission to view activity logs.');
        }

        $query = AdminActivity::with('admin')->latest();

        // Filter by admin if specified and has permission
        if ($request->has('admin_id') && $request->admin_id) {
            if (!auth('admin')->user()->can('activity.logs.view.subadmin')) {
                abort(403, 'You do not have permission to view sub-admin activities.');
            }
            $query->byAdmin($request->admin_id);
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->byAction($request->action);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->has('end_date') && $request->end_date 
                ? $request->end_date . ' 23:59:59' 
                : now()->format('Y-m-d') . ' 23:59:59';
            
            $query->dateRange($startDate, $endDate);
        }

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('admin_name', function ($activity) {
                    return '<strong>' . e($activity->admin->name) . '</strong><br><small class="text-muted">' . e($activity->admin->email) . '</small>';
                })
                ->addColumn('action_badge', function ($activity) {
                    return '<span class="badge badge-' . $activity->getActionBadgeClass() . '">' . ucfirst($activity->action) . '</span>';
                })
                ->editColumn('description', function ($activity) {
                    return $activity->description ?? '-';
                })
                ->editColumn('ip_address', function ($activity) {
                    return $activity->ip_address ?? '-';
                })
                ->editColumn('created_at', function ($activity) {
                    return $activity->created_at->format('M d, Y H:i A');
                })
                ->addColumn('action', function ($activity) {
                    return '<a href="' . route('admin.activity-logs.show', $activity->id) . '" class="btn btn-info btn-sm" title="' . __('View Details') . '"><i class="fas fa-eye"></i></a>';
                })
                ->rawColumns(['admin_name', 'action_badge', 'action'])
                ->make(true);
        }

        $admins = Admin::where('is_super_admin', 0)->get(); // Get sub-admins only
        $actions = AdminActivity::distinct('action')->pluck('action');

        return view('admin.activity-logs.index', compact('admins', 'actions'));
    }

    /**
     * Display the specified admin activity.
     */
    public function show(AdminActivity $activity)
    {
        // Check if admin has permission to view activity logs
        if (!auth('admin')->user()->can('activity.logs.view')) {
            abort(403, 'You do not have permission to view activity logs.');
        }

        // Check if trying to view sub-admin activity without permission
        if ($activity->admin_id !== auth('admin')->id() && !auth('admin')->user()->can('activity.logs.view.subadmin')) {
            abort(403, 'You do not have permission to view this activity log.');
        }

        return view('admin.activity-logs.show', compact('activity'));
    }

    /**
     * Get activity logs for a specific admin (AJAX endpoint)
     */
    public function getAdminActivities(Request $request, Admin $admin)
    {
        // Check if admin has permission to view sub-admin activities
        if (!auth('admin')->user()->can('activity.logs.view.subadmin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $activities = AdminActivity::byAdmin($admin->id)
            ->with('admin')
            ->latest()
            ->limit(50)
            ->get();

        return response()->json($activities);
    }
}
