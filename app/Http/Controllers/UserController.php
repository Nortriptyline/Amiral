<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function update_current_club(Request $request)
    {
        $club = Club::findOrFail($request->club_id);


        if ($request->user()->can('switchCurrentClub', $club)) {
            if (!$this->users->switchCurrentClub($request->user(), $club)) {
                abort(403);
            }
        }
        

        return redirect(config('fortify.home'), 303);
    }
}
