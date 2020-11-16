<?php

namespace App\Repositories;

use App\Models\Club;
use App\Models\Team;

interface TeamRepositoryInterface
{
    
    public function store($user, Club $club, $team);
}