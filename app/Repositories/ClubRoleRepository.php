<?php

namespace App\Repositories;

use App\Repositories\UserRepository;
use App\Repositories\ClubRoleRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Models\Club;
use App\Models\ClubRole;
use App\Rules\UniqueInClub;
use App\Validations\ClubRoleValidation;
use Illuminate\Support\Str;

class ClubRoleRepository implements ClubRoleRepositoryInterface
{
    use ClubRoleValidation;

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

        if ($user->can('editRoles', $club)) {
            $input['name'] = Str::ucfirst($input['name']);

            Validator::make($input, [
                'name' => $this->clubRules($club),
            ])->validateWithBag('createClubRole');

            $slug = Str::of($input['name'])->slug('-');
            $slug = !$club->slug_exists($slug)
                ? $slug
                : Str::uuid();


            $role = $club->roles()->create([
                'name' => $input['name'],
                'slug' => $slug,
            ]);

            return $role;
        }
    }

    /**
     * 
     */
    public function update($user, ClubRole $role, array $input)
    {
        $input['name'] = Str::ucfirst($input['name']);
        $club = $role->club()->first();

        Validator::make($input, [
            'name' => $this->clubRules($club),
        ])->validateWithBag('UpdateClubRole');

        $role->name = $input['name'];
        $role->save();

        return $role;
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
