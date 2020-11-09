<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public function delete(Request $request, $notification)
    {
        $user = Auth::user();
        $user->notifications->where('id', $notification)
            ->first()
            ->delete();

        return back();
    }

    public function mark_as_read(Request $request, $notification)
    {
        $user = Auth::user();
        if ($user->unreadNotifications->where('id', $notification)->count() > 0) {
            $user->unreadNotifications->where('id', $notification)->markAsRead();
        } else {
            $affected = DB::table('notifications')
                ->where('id', $notification)
                ->update(['read_at' => null]);
        }

        $user->refresh();

        return response()->json([
            'notification' => $user->notifications
                ->where('id', $notification)
                ->first(),
        ]);
    }
}
