<?php

namespace Modules\GlobalSetting\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\GlobalSetting\app\Models\AdminNotification;
use Yajra\DataTables\Facades\DataTables;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = AdminNotification::query();

        $query->when(request()->filled('search'), function ($q) {
            $q->where('message', 'like', '%'.request('search').'%')->orWhere('title', 'like', '%'.request('search').'%');
        });

        $query->when(request()->filled('type'), function ($q) {
            $type = request('type') == 'unread' ? 0 : 1;

            $q->where('is_read', $type);
        });

        $query->when(request()->filled('alert_type'), function ($q) {
            $q->where('type', request('alert_type'));
        });

        $query->orderBy('created_at', request('order') == 'asc' ? 'asc' : 'desc');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('select', function ($message) {
                    return '<input class="form-check-input select_single" data-id="' . $message->id . '" type="checkbox">';
                })
                ->addColumn('title_col', function ($message) {
                    return '<span class="text-' . e($message->type) . '">'
                        . htmlDecode($message->title) . ' (' . str($message->type)->title() . ')</span>';
                })
                ->addColumn('created_at_col', function ($message) {
                    $html = '';
                    if ($message->is_read == 1) {
                        $html .= '<i class="fas fa-check-circle text-primary" title="' . __('Read at') . ' ' . formattedDateTime($message->updated_at) . '"></i> ';
                    }
                    return $html . formattedDateTime($message->created_at);
                })
                ->addColumn('action', function ($message) {
                    return '<a class="btn btn-success btn-sm" href="' . route('admin.notifications.show', $message->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a> '
                        . '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" onclick="deleteData(' . $message->id . ')"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['select', 'title_col', 'created_at_col', 'action'])
                ->make(true);
        }

        $data['totalNotificationsCount'] = AdminNotification::count();
        $data['unreadCount'] = AdminNotification::where('is_read', 0)->count();
        $data['readCount'] = $data['totalNotificationsCount'] - $data['unreadCount'];
        $data['infoCount'] = AdminNotification::where('type', 'info')->count();
        $data['successCount'] = AdminNotification::where('type', 'success')->count();
        $data['warningCount'] = AdminNotification::where('type', 'warning')->count();
        $data['dangerCount'] = AdminNotification::where('type', 'danger')->count();

        self::forgetCache();

        return view('globalsetting::notifications.index', $data);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $notification = AdminNotification::findOrFail($id);

        $notification->update([
            'is_read' => 1,
        ]);

        $notification->refresh();

        self::forgetCache();

        return view('globalsetting::notifications.show', compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        AdminNotification::findOrFail($id)->delete();

        self::forgetCache();

        return to_route('admin.notifications.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'success',
        ]);
    }

    public function markAsRead(Request $request)
    {
        if ($request->has('ids') && $request->filled('ids')) {
            AdminNotification::whereIn('id', $request->ids)->update(['is_read' => true]);
        } else {
            AdminNotification::where('is_read', false)->update(['is_read' => true]);
        }

        self::forgetCache();

        $notification = __('Messages marked as read successfully');

        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return back()->with($notification);
    }

    public static function forgetCache()
    {
        Cache::forget('admin-notifications');
        Cache::rememberForever('admin-notifications', function () {
            return AdminNotification::where('is_read', 0)->latest()->get();
        });
    }

    public function deleteAll(Request $request)
    {
        if ($request->has('ids') && $request->filled('ids')) {
            AdminNotification::whereIn('id', $request->ids)->delete();
        } else {
            AdminNotification::query()->delete();
        }

        self::forgetCache();

        return back()->with([
            'message' => $request->filled('ids') ? 'Deleted successfully' : 'Deleted successfully',
            'alert-type' => 'success',
        ]);
    }
}
