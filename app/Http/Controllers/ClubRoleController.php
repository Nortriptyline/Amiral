<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRole;
use App\Repositories\ClubRoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ClubRoleController extends Controller
{

    private $roles;

    public function __construct(ClubRoleRepository $roles)
    {
        $this->roles = $roles;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $club = Club::find($request->user()->current_club_id);
        $this->roles->create($request->user(), $club, $request->all());
        return back();
    }

    /**
     * Update an existing clubrole Name
     *
     * @param  \Illuminate\Http\Request $request
     * @param  App\Models\ClubRole $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClubRole $role)
    {
        $this->roles->update($request->user(), $role, $request->all());
        return back();
    }


    /**
     * Delete an existing resource in storage
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, ClubRole $role)
    {
        if (!Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ])->errorBag('DeleteClubRole');
        }

        $this->roles->delete($request->user(), $role);

        return back();

    }
}
