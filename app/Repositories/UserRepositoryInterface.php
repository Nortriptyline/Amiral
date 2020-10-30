<?php

namespace App\Repositories;

use App\Models\Club;

interface UserRepositoryInterface
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function switchCurrentClub($user, Club $club);
}
