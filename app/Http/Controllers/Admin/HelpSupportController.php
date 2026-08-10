<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpSupport;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HelpSupportController extends Controller
{
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('complaints.view');

        $query = HelpSupport::with('user')->latest();

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('user_name', function ($complaint) {
                    if ($complaint->user) {
                        return e($complaint->user->name) . '<br><small>' . ucfirst($complaint->user->user_type) . '</small>';
                    }

                    return 'User Deleted';
                })
                ->addColumn('priority_badge', function ($complaint) {
                    if ($complaint->priority == 'high') {
                        return '<span class="badge badge-danger">High</span>';
                    } elseif ($complaint->priority == 'medium') {
                        return '<span class="badge badge-warning">Medium</span>';
                    }

                    return '<span class="badge badge-info">Low</span>';
                })
                ->editColumn('created_at', function ($complaint) {
                    return $complaint->created_at->format('d M, Y');
                })
                ->addColumn('action', function ($complaint) {
                    $html = '';

                    if (checkAdminHasPermission('complaints.view')) {
                        $html .= '<a href="' . route('admin.help-supports.show', $complaint->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a> ';
                    }

                    if (checkAdminHasPermission('complaints.delete')) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.help-supports.destroy', $complaint->id) . '"><i class="fa fa-trash"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['user_name', 'priority_badge', 'action'])
                ->make(true);
        }

        return view('admin.help_supports.index');
    }

    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('complaints.view');

        $complaint = HelpSupport::with(['user', 'booking.customer', 'booking.technician'])->findOrFail($id);
        return view('admin.help_supports.show', compact('complaint'));
    }

    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('complaints.delete');

        $complaint = HelpSupport::findOrFail($id);
        $complaint->delete();
        return redirect()->route('admin.help-supports.index')->with('success', 'Complaint deleted successfully.');
    }
}
