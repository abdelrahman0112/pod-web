<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display user's notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->notifications();

        // Filter by read status
        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'unread':
                    $query->whereNull('read_at');
                    break;
                case 'read':
                    $query->whereNotNull('read_at');
                    break;
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', 'like', "%{$request->type}%");
        }

        $notifications = $query->paginate(20);

        if ($request->expectsJson()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found',
        ], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Delete a notification.
     */
    public function destroy($notificationId)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found',
        ], 404);
    }

    /**
     * Clear all notifications.
     */
    public function clear()
    {
        Auth::user()->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared',
        ]);
    }

    /**
     * Get notifications for AJAX requests (real-time updates).
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $notifications = $user->notifications()
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get();

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            }),
            'unread_count' => $unreadCount,
            'has_more' => $notifications->count() === $limit,
        ]);
    }

    /**
     * Update notification preferences.
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notification_types' => 'array',
            'notification_types.*' => 'string|in:events,jobs,hackathons,posts,messages,team_invites',
        ]);

        $user = Auth::user();

        // This would typically be stored in a user preferences table
        // For now, we'll just return success
        // You could add a user_notification_preferences table/model

        return response()->json([
            'success' => true,
            'message' => 'Notification preferences updated',
        ]);
    }

    /**
     * Test notification (for development/admin).
     */
    public function test(Request $request)
    {
        if (! Auth::user()->hasRole(['admin', 'super_admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|string|in:event_reminder,job_application,post_liked,team_invite',
            'message' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Create a test notification
        $user->notify(new \App\Notifications\TestNotification($validated['message']));

        return response()->json([
            'success' => true,
            'message' => 'Test notification sent',
        ]);
    }
}
