<?php

namespace App\Repositories;

use App\Repositories\ClubRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use App\Models\Club;
use App\Models\ClubRole;
use App\Models\User;
use App\Notifications\UserInvitedToClub;
use App\Rules\ExistsInClub;
use App\Rules\UniqueInClub;
use Illuminate\Support\Facades\Auth;

class ClubRepository implements ClubRepositoryInterface
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
    public function create($user, array $input)
    {
        if ($user->can('create', Club::class)) {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
            ])->validateWithBag('createClub');

            $club = Club::create([
                'name' => $input['name'],
            ]);

            $role = $club->roles()->create([
                'slug' => 'admin',
                'name' => 'Administrator',
            ]);

            $this->users->switchCurrentClub($user, $club);

            return $user->clubs()->attach($club, [
                'club_role_id' => $role->id,
                'confirmed_at' => now()
            ]);
        }
    }

    public function add_member(Club $club, $input)
    {
        $user = Auth::user();

        if ($user->can('update', $club)) {


            Validator::make($input, [
                'email' => ['required', 'email', 'exists:users,email', new UniqueInClub([
                    'club' => $club,
                    'pivot' => 'users'
                ])],
                'role' => ['required', new ExistsInClub([
                    'club' => $club,
                    'pivot' => 'roles',
                    'field' => 'id',
                ])],
            ])->validateWithBag('addClubMember');

            $summoned = User::where('email', $input['email'])->firstOrFail();
            $role = ClubRole::find($input['role']);

            return $this->inviteUser($club, $summoned, $role);
        }
    }

    public function updateInformations(Club $club, $input)
    {
        $user = Auth::user();

        if ($user->can('update', $club)) {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['string', 'max:65535'],
            ])->validateWithBag('updateClubInformation');

            $club->name = $input['name'];
            $club->description = $input['description'];

            $club->save();
        }
    }

    public function inviteUser(Club $club, User $user, ClubRole $role)
    {
        $club->users()->attach($user, ['club_role_id' => $role->id]);
        $user->notify(new UserInvitedToClub([
            'club' => $club,
            'role' => $role,
        ]));

        return $club;
    }

    public function confirmInvitation(Club $club, User $user)
    {
        $club->users()->updateExistingPivot($user, ['confirmed_at' => now()]);

        if (!$user->current_club_id) {
            $this->users->switchCurrentClub($user, $club);
        }

        return $club;
    }
}
