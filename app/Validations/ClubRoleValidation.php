<?php

namespace App\Validations;

use App\Models\Club;
use App\Rules\UniqueInClub;

trait ClubRoleValidation
{
    protected function clubRules(Club $club)
    {
        return ['required', 'string', 'max:255', new UniqueInClub([
            'club' => $club,
            'pivot' => 'roles',
        ])];
    }
}
