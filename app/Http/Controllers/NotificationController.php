<?php

namespace App\Http\Controllers;

use App\Notifications\UserInvitedToClub;
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
        // If read is true, mark as read
        $read_at = $request->markAsRead ? now() : null;
        
        DB::table('notifications')
            ->where('id', $notification)
            ->update([
                'data' => json_encode($request->datas),
                'read_at' => $read_at
            ]);

        return back();
    }

    public function read_all(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}
