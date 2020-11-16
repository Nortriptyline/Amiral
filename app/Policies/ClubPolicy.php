<?php

namespace App\Policies;

use App\Models\Club;
use App\Models\ClubRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ClubPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function view(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Auth::check();
    }

    public function edit(User $user, Club $club)
    {
        return $user->isClubAdmin($club);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function update(User $user, Club $club)
    {
        return $user->isClubAdmin($club);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function delete(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function restore(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function forceDelete(User $user, Club $club)
    {
        //
    }

    public function switchCurrentClub(User $user, Club $club)
    {
        $club = Club::whereHas('users', function (Builder $query) use ($user){
            $query->where('user_id', $user->id);
        })
        ->where('id', $club->id)
        ->get();

        return ($club->count() > 0);
    }

    public function editRoles(User $user, Club $club)
    {
        return $user->isClubAdmin($club);
    }

    public function removeMember(User $user, Club $club, User $leaver)
    {
        $accept = false;

        /**
         * Owner ne peut pas quitter le club. 
         * Le capitaine est le dernier à quitter le navire !
         */ 
        if ($leaver->ownClub($club)) {
            return false;
        }

        /**
         * Si l'utilisateur est kick leaver != user, on vérifie l'admin
         * Si l'utilisateur leave (leaver == user), on l'autorise à partir.
         */
        return $leaver->id != $user->id 
            ? $user->isClubAdmin($club)
            : true;
    }
}
