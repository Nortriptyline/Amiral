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
        'role',
        'UnreadNotificationsLength',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'notifications',
        'clubs',
        'club_invitations',
    ];

    /**
     * Many to Many club - user
     */
    public function clubs()
    {
        return $this->belongsToMany('App\Models\Club')
            ->whereNotNull('confirmed_at')
            ->withTimestamps();
    }

    public function club_invitations()
    {
        return $this->belongsToMany('App\Models\Club', 'club_user', 'user_id', 'club_id')
            ->whereNull('confirmed_at')
            ->withTimestamps();
    }

    public function club_role(Club $club)
    {
        $data = $club->users()
            ->where('user_id', $this->id)
            ->first();

        return ClubRole::find($data->pivot->club_role_id);
    }

    public function hasClubRole(Club $club, $slug)
    {
        return $this->club_role($club)->slug === $slug;
    }

    public function isClubAdmin(Club $club)
    {
        return $this->club_role($club)->slug === 'admin';
    }

    public function getRoleAttribute()
    {
        $active_user = Auth::user();
        $club = Club::find($active_user->current_club_id);

        return isset($club)
            ? $this->club_role($club)
            : null;
    }

    public function getUnreadNotificationsLengthAttribute()
    {
        return $this->unreadNotifications->count();
    }
}
