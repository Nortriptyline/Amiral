<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function toggle(Request $request, $notification)
    {
        $user = Auth::user();
        
        Validator::make($request->all(), [
            'read' => ['required', 'boolean']
        ])->validateWithBag("toggleReadNotification");

        // If read is true, mark as read
        if ($request->read) {
            $request->user()->unreadNotifications->where('id', $notification)->markAsRead();
        } else {
            $affected = DB::table('notifications')
                ->where('id', $notification)
                ->update(['read_at' => null]);

        }

        // return back();
    }
}
