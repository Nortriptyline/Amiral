<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Club;

interface ClubRepositoryInterface
{
    /**
     * Validate and create a new club for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input);

    /**
     * Validate and update informations for a club.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function updateInformations(Club $club, array $input);
}
