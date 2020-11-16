<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'description',
        'owner',
    ];

    /**
     * Many to Many club - user
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    // Return role names
    public function roles()
    {
        return DB::table('club_user')
            ->select('role as slug')
            ->distinct()
            ->get();
    }
}
