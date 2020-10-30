<?php

namespace App\Repositories;

use App\Models\Club;
use App\Models\ClubRole;

interface ClubRoleRepositoryInterface
{
    /**
     * Validate and create a new club for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, Club $club, array $input);

    /**
     * Validate and update informations for a club.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function delete($user, ClubRole $role);
}
