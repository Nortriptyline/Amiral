<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user->unreadNotifications->where('id', $notification)->markAsRead();

        return response()->json([
            'notification' => $user->notifications
                ->where('id', $notification)
                ->first(),
        ]);
    }
}
