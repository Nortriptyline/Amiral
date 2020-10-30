<?php

namespace App\Repositories;

use App\Repositories\UserRepositoryInterface;
use App\Models\Club;

class UserRepository implements UserRepositoryInterface
{
    public function switchCurrentClub($user, Club $club)
    {
        $user->current_club_id = $club->id;
        return $user->save();
    }
}
