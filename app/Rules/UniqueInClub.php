<?php

namespace App\Rules;

use App\Models\Club;
use Illuminate\Contracts\Validation\Rule;

class UniqueInClub implements Rule
{

    private $params;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $club = $this->params['club'];
        $pivot = $this->params['pivot'];

        return $club->$pivot->where($attribute, $value)->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already exists for this club.';
    }
}
