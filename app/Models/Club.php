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
        'name',
    ];

    /**
     * Many to Many club - user
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function roles()
    {
        return $this->hasMany('App\Models\ClubRole');
    }
}
