<?php

namespace App\Repositories;

use App\Repositories\UserRepositoryInterface;
use App\Models\Club;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function switchCurrentClub($user, Club $club)
    {
        $user->current_club_id = $club ? $club->id : null;
        
        return $user->save();
    }

    public function switchDefaultCurrentClub(User $user)
    {
        if ($club = $user->clubs->where('owner', $user->id)->first()) {
            $this->switchCurrentClub($user, $club);
        } else if ($club = $user->clubs()->first()){
            $this->switchCurrentClub($user, $club);
        } else {
            $user->current_club_id = null;
            return $user->save();
        }
    }

}
