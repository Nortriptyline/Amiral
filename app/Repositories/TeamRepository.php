<?php

namespace App\Repositories;

use App\Models\Club;
use App\Models\Team;
use Illuminate\Support\Facades\Validator;

class TeamRepository
{
    
    public function create($user, Club $club, $team)
    {
        if ($user->can('create', [Team::class, $club])) {
            Validator::make($team, [
                'name' => ['required', 'string', 'max:64'],
                'description' => ['nullable', 'string', 'max:65,535'],
            ])->validateWithBag('createTeam');

            return $club->teams()->create([
                'name' => $team['name'],
                'description' => $team['description'],
            ]);
        }
    }
}