<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendAppPushNotificationJob;
use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Http\Request;

class AppNotificationController extends Controller
{
    public function index()
    {
        $notifications = AppNotification::latest()->paginate(20);

        return view('admin.app-notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.app-notifications.create');
    }

    public function searchUsers(Request $request)
    {
        $ids = array_filter(array_map('intval', (array) $request->get('ids', [])));

        if (!empty($ids)) {
            $users = User::query()
                ->whereIn('id', $ids)
                ->whereIn('user_type', ['customer', 'technician'])
                ->get(['id', 'name', 'email', 'user_type']);

            return response()->json([
                'results' => $users->map(fn (User $user) => $this->formatSearchUser($user)),
            ]);
        }

        $term = trim((string) $request->get('q', ''));

        if (strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $users = User::query()
            ->whereIn('user_type', ['customer', 'technician'])
            ->where(function ($query) use ($term) {
                $query->where('email', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%");
            })
            ->orderBy('email')
            ->limit(20)
            ->get(['id', 'name', 'email', 'user_type']);

        return response()->json([
            'results' => $users->map(fn (User $user) => $this->formatSearchUser($user)),
        ]);
    }

    private function formatSearchUser(User $user): array
    {
        return [
            'id' => $user->id,
            'text' => $user->name . ' (' . $user->email . ')',
            'email' => $user->email,
            'name' => $user->name,
            'user_type' => $user->user_type,
            'user_type_label' => ucfirst($user->user_type),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'target_audience' => 'required|in:all,customers,technicians,specific_users',
            'user_ids' => 'required_if:target_audience,specific_users|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
            'send_now' => 'nullable|boolean',
        ]);

        $targetUserIds = $request->target_audience === 'specific_users'
            ? array_values(array_unique(array_map('intval', $request->input('user_ids', []))))
            : null;

        if ($request->target_audience === 'specific_users' && empty($targetUserIds)) {
            return back()->withInput()->with([
                'message' => 'Please select at least one user.',
                'alert-type' => 'error',
            ]);
        }

        $notification = AppNotification::create([
            'title' => $request->title,
            'body' => $request->body,
            'target_audience' => $request->target_audience,
            'target_user_ids' => $targetUserIds,
            'payload' => [
                'screen' => $request->input('screen'),
                'reference_id' => $request->input('reference_id'),
            ],
            'status' => $request->boolean('send_now') ? 'queued' : 'draft',
            'created_by' => auth('admin')->id(),
            'scheduled_at' => $request->boolean('send_now') ? now() : null,
        ]);

        if ($request->boolean('send_now')) {
            SendAppPushNotificationJob::dispatch($notification->id);
        }

        return redirect()->route('admin.app-notifications.index')->with([
            'message' => $request->boolean('send_now')
                ? 'Notification queued for delivery.'
                : 'Notification saved as draft.',
            'alert-type' => 'success',
        ]);
    }

    public function send($id)
    {
        $notification = AppNotification::findOrFail($id);

        if ($notification->status === 'sent') {
            return back()->with(['message' => 'Already sent.', 'alert-type' => 'warning']);
        }

        $notification->update(['status' => 'queued', 'scheduled_at' => now()]);
        SendAppPushNotificationJob::dispatch($notification->id);

        return back()->with(['message' => 'Notification queued for delivery.', 'alert-type' => 'success']);
    }

    public function destroy($id)
    {
        $notification = AppNotification::findOrFail($id);
        $notification->delete();

        return back()->with([
            'message' => 'Admin notification deleted. User inboxes are unchanged.',
            'alert-type' => 'success',
        ]);
    }
}
