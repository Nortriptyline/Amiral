<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistsInClub implements Rule
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
        $field = $this->params['field'] ?? $attribute;
        
        return $club->$pivot->where($field, $value)->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute does not exists.';
    }
}
