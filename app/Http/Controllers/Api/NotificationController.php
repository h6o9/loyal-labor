<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = UserNotification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'unread_count' => UserNotification::where('user_id', $request->user()->id)->where('is_read', false)->count(),
            'data' => $notifications,
        ]);
    }

    public function markRead(Request $request, $id)
    {
        $notification = UserNotification::where('user_id', $request->user()->id)->findOrFail($id);
        $notification->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
    }

    public function markAllRead(Request $request)
    {
        UserNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }

    public function clearAll(Request $request)
    {
        $deletedCount = UserNotification::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared.',
            'deleted_count' => $deletedCount,
        ]);
    }
}
