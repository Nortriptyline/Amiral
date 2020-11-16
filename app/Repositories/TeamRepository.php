<?php

namespace App\Repositories;

use App\Models\Club;
use App\Models\Team;
use Illuminate\Support\Facades\Validator;

class TeamRepository implements TeamRepositoryInterface
{
    public function store($user, Club $club, $input)
    {
        if ($user->can('edit', $club)) {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:64'],
                'description' => ['nullable', 'string', 'max:65,535'],
            ])->validateWithBag('createTeam');

            return $club->teams()->create([
                'name' => $input['name'],
                'description' => $input['description'],
            ]);
        }
    }
}
