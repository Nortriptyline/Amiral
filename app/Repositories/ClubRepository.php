<?php

namespace App\Repositories;

use App\Repositories\ClubRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use App\Models\Club;

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
                'name' => 'Administrator',
            ]);

            $this->users->switchCurrentClub($user, $club);

            return $user->clubs()->attach($club, ['club_role_id' => $role->id]);
        }
    }
}
