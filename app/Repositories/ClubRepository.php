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
                'description' => ['string', 'max:65,535'],
            ])->validateWithBag('createClub');

            $club = Club::create([
                'name' => $input['name'],
                'description' => $input['description'],
                'owner' => $user->id,
            ]);

            $user->clubs()->attach($club, [
                'role' => 'admin',
                'confirmed_at' => now(),
            ]);

            return $this->users->switchCurrentClub($user, $club);
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
                'role' => ['required', 'in:admin,editor'],
            ])->validateWithBag('addClubMember');

            $summoned = User::where('email', $input['email'])->first();

            return $this->inviteUser($club, $summoned, $input['role']);
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

    public function inviteUser(Club $club, User $user, $role)
    {
        $club->users()->attach($user, ['role' => $role]);
        $user->notify(new UserInvitedToClub([
            'user' => ['id' => $user->id, 'name' => $user->name, 'role' => $role],
            'club' => ['id' => $club->id, 'name' => $club->name],
            'status' => 'pending'
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

    public function withdraw(Club $club, User $user)
    {
        if ($user->can('withdraw', $club)) {
            $club->users()->detach($user->id);
        }
    }
}
