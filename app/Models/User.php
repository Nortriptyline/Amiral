<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'unreadNotificationsLength',
        'permissions',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'notifications',
        'clubs',
        'joined_clubs',
    ];

    /**
     * Many to Many club - user
     */
    public function clubs()
    {
        return $this->belongsToMany('App\Models\Club')
            ->withPivot('role');
    }

    public function joined_clubs()
    {
        return $this->clubs()
            ->whereNotNull('confirmed_at');
    }

    public function ownClub(Club $club)
    {
        return $this->id == $club->owner;
    }

    public function isInClub(Club $club)
    {
        return $this->clubs()
            ->where('club_id', $club->id)
            ->first();
    }

    public function roleInClub(Club $club)
    {
        if ($this->isInClub($club)) {
            return $this->clubs()->where('club_id', $club->id)->first()->pivot->role;
        } else {
            return false;
        }
    }

    public function isClubAdmin(Club $club)
    {
        return $this->roleInClub($club) == 'admin';
    }

    public function getUnreadNotificationsLengthAttribute()
    {
        return $this->unreadNotifications->count();
    }

    public function getPermissionsAttribute()
    {
        $club = $this->current_club_id ? Club::find($this->current_club_id) : null;

        return [
            'editCurrentClub' => $this->can('edit', $club),
        ];
    }
}
