<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected $with = [
        'roles',
    ];

    /**
     * Many to Many club - user
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('club_role_id');
    }

    public function roles()
    {
        return $this->hasMany('App\Models\ClubRole');
    }

    public function slug_exists($value)
    {
        return $this->roles()->where('slug', $value)->count() > 0;
    }
}
