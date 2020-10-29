<?php

namespace App\Repositories;

use App\Repositories\UserRepository;
use App\Repositories\ClubRoleRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Models\Club;
use App\Models\ClubRole;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class ClubRoleRepository implements ClubRoleRepositoryInterface
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
    /**
     * Validate and create a new team for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, Club $club, array $input)
    {
        // /!\ Check if role name and slug exists only for this club. Not all clubs
        if ($user->can('editRoles', $club)) {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'alpha_dash', 'unique:club_roles,slug'],
            ])->validateWithBag('createClubRole');
            
            $role = $club->roles()->create([
                'name' => $input['name'],
                'slug' => $input['slug'],
            ]);

            return $role;
        }
    }

    public function delete($user, ClubRole $role)
    {
        if ($user->can('delete', $role)) {
            
            // Can not delete administrator role
            if ($role->slug === 'admin') {
                abort(403, 'This role deletion is not allowed.');
            }

            $role->delete();
        }
    }
}
