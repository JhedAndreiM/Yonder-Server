<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function markAllRead(Request $request)
    {
        $userId = auth()->id();

        DB::table('notifications')
            ->where('user_id', $userId)
            ->where(function ($q) {
                $q->where('is_read', false)->orWhere('is_read', 0);
            })
            ->update(['is_read' => true]);

        return response()->json(['status' => 'ok']);
    }
    public function loadMore(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->json([], 401); // not logged in
        }

        $offset = (int) $request->query('offset', 0);

        $notifications = \DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => (bool) $notification->is_read,
                    'time_ago' => \Carbon\Carbon::parse($notification->created_at)->diffForHumans(),
                ];
            });

        return response()->json($notifications);
    }

}
