<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jrean\UserVerification\Traits\UserVerification;

class User extends Authenticatable
{
    use Notifiable;
    use UserVerification;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'username', 'email', 'password', 'blocked_on', 'affiliate',
        'notification_followers_add_media', 'notification_following', 'notification_comments',
        'current_account_balance', 'all_time_account_balance', 'commissions_video',
        'commissions_audio', 'commissions_image', 'paypal_email', 'affiliate_id', 'referred_by',
        'current_referral_balance', 'all_time_referral_balance', 'payza_email', 'verified',
        'first_name', 'last_name', 'phone', 'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    //

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function fullName(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('owner');
    }

    public function invitations(){
        return $this->hasMany(Invitation::class);
    }

    // Espero eliminar esto
    // public function allTeams(){ // Retorno equipos propios y con invitación
    //     $acceptedInvitations = $this->invitations()->where('status', 'accepted')->get();
    //     $invitedTeams = $acceptedInvitations->map(function($invitation){
    //         return $invitation->team()->with('events')->first();
    //     });
    //     $ownedTeams = $this->teams()->with('events')->get();
    //     return $invitedTeams->merge($ownedTeams);
    // }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function pointsSum()
    {
        return $this->points()->sum('points');
    }

    public function following()
    {
        return $this->hasMany(Followers::class, 'user_id', 'id');
    }

    public function followers()
    {
        return $this->hasMany(Followers::class, 'follower_id', 'id');
    }

    public function isFollow($id)
    {
        return Followers::where('user_id', $this->id)->where('follower_id', $id)->count();
    }

    public function flags()
    {
        return $this->hasMany(Flag::class);
    }

    public function isBlocked()
    {
        if ($this->blocked_on && Carbon::now() >= $this->blocked_on) {
            return true;
        } else {
            return false;
        }
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function lineups(){
        return $this->hasMany(LineUp::class);
    }
}
