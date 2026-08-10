<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Check if staff has permission to view jobs

        $defaultLocation = 'University of Management & Technology C-II Block C 2 Phase 1 Johar Town, Lahore, 54770, Pakistan';

        $query = StaffJob::with(['shop', 'assignedBy', 'assignedTo'])
            ->where('assigned_to', Auth::guard('staff')->id())
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc');

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('shop_name', function ($job) {
                    return e($job->shop->shop_name ?? 'N/A');
                })
                ->addColumn('assigned_by_name', function ($job) {
                    return e($job->assignedBy->name ?? 'N/A');
                })
                ->addColumn('scheduled_date', function ($job) {
                    if ($job->scheduled_date) {
                        $html = e($job->scheduled_date);
                        if ($job->scheduled_time) {
                            $html .= '<br><small>' . e($job->scheduled_time) . '</small>';
                        }

                        return $html;
                    }

                    return __('Not scheduled');
                })
                ->addColumn('description_btn', function ($job) {
                    return '<button type="button" class="btn btn-sm btn-info view-description-btn" '
                        . 'data-id="' . $job->id . '" '
                        . 'data-shop="' . e($job->shop->shop_name ?? 'N/A') . '" '
                        . 'data-assigned-by="' . e($job->assignedBy->name ?? 'N/A') . '" '
                        . 'data-scheduled-date="' . e($job->scheduled_date) . '" '
                        . 'data-scheduled-time="' . e($job->scheduled_time) . '" '
                        . 'data-description="' . addslashes($job->description ?? 'No description available.') . '">'
                        . '<i class="fas fa-eye"></i> ' . __('View Description') . '</button>';
                })
                ->addColumn('notes_btn', function ($job) {
                    if ($job->notes && $job->notes != '') {
                        return '<button type="button" class="btn btn-sm btn-secondary view-notes-btn" '
                            . 'data-id="' . $job->id . '" '
                            . 'data-shop="' . e($job->shop->shop_name ?? 'N/A') . '" '
                            . 'data-assigned-by="' . e($job->assignedBy->name ?? 'N/A') . '" '
                            . 'data-scheduled-date="' . e($job->scheduled_date) . '" '
                            . 'data-scheduled-time="' . e($job->scheduled_time) . '" '
                            . 'data-notes="' . addslashes($job->notes) . '">'
                            . '<i class="fas fa-eye"></i> ' . __('View Notes') . '</button>';
                    }

                    return '<span class="text-muted">' . __('No notes') . '</span>';
                })
                ->addColumn('from_location', function ($job) use ($defaultLocation) {
                    return e($job->assignedTo->location ?? $defaultLocation);
                })
                ->addColumn('to_location', function ($job) {
                    return e($job->shop->location ?? $job->shop->address ?? 'N/A');
                })
                ->addColumn('navigation', function ($job) use ($defaultLocation) {
                    $from = $job->assignedTo->location ?? $defaultLocation;
                    $to = $job->shop->location ?? $job->shop->address ?? 'Lahore';

                    return '<a href="https://www.google.com/maps/dir/' . urlencode($from) . '/' . urlencode($to) . '" target="_blank" class="btn btn-sm btn-info">'
                        . '<i class="fas fa-location-arrow"></i> ' . __('Navigate') . '</a>';
                })
                ->addColumn('status_badge', function ($job) {
                    if ($job->status == 'pending') {
                        return '<span class="badge badge-warning">' . __('Pending') . '</span>';
                    }

                    return '<span class="badge badge-success">' . __('Done') . '</span>';
                })
                ->addColumn('action', function ($job) {
                    if ($job->status == 'pending') {
                        return '<button type="button" class="btn btn-sm btn-success mark-done-btn" data-id="' . $job->id . '" data-shop="' . e($job->shop->shop_name ?? 'N/A') . '">'
                            . '<i class="fas fa-check"></i> ' . __('Done') . '</button>';
                    }

                    return '<button type="button" class="btn btn-sm btn-warning mark-undone-btn" data-id="' . $job->id . '" data-shop="' . e($job->shop->shop_name ?? 'N/A') . '">'
                        . '<i class="fas fa-undo"></i> ' . __('Undo') . '</button>';
                })
                ->rawColumns(['shop_name', 'assigned_by_name', 'scheduled_date', 'description_btn', 'notes_btn', 'from_location', 'to_location', 'navigation', 'status_badge', 'action'])
                ->make(true);
        }

        return view('staff.jobs.index');
    }
    
    public function markAsDone($id)
    {
        // Check if staff has permission to edit jobs
        
        $job = StaffJob::where('assigned_to', Auth::guard('staff')->id())
            ->findOrFail($id);
            
        $job->status = 'done';
        $job->save();
        
        return response()->json([
            'message' => 'Job marked as done successfully!',
            'status' => $job->status
        ]);
    }
    
    public function markAsUndone($id)
    {
        // Check if staff has permission to edit jobs
        $job = StaffJob::where('assigned_to', Auth::guard('staff')->id())
            ->findOrFail($id);
            
        $job->status = 'pending';
        $job->save();
        
        return response()->json([
            'message' => 'Job marked as pending successfully!',
            'status' => $job->status
        ]);
    }
    
    public function show($id)
    {
        // Check if staff has permission to view jobs
        
        $job = StaffJob::with(['shop', 'assignedBy'])
            ->where('assigned_to', Auth::guard('staff')->id())
            ->findOrFail($id);
            
        return view('staff.jobs.show', compact('job'));
    }
}
