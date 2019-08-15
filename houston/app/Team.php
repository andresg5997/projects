<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Team extends Model
{
	protected $table = 'teams';
    protected $fillable = ['name', 'sport_id', 'timezone', 'country', 'zip', 'city', 'founded_at', 'avatar'];
    protected $guarded = ['id'];
    // protected $dates = ['created_at', 'updated_at', 'founded_at'];

    public function getOwnerAttribute()
    {
        return $this->user()->first();
    }

    public function players(){
    	return $this->hasMany(Player::class);
    }

    public function users(){
    	return $this->belongsToMany(User::class)->withPivot('owner');
    }

    public function user()
    {
        return $this->belongsToMany(User::class)->wherePivot('owner', '1');
    }

    public function sport(){
    	return $this->belongsTo(Sport::class);
    }

    public function events(){
        return $this->hasMany(Event::class);
    }

    public function archives(){
        return $this->hasMany(Archive::class);
    }

    public function sendInvitation($email){
        // b2e55668de481222a5d486308214081c7579831b SPARKPOST api
        return $count;
    }

    public function isOwner($id){
        if($this->owner->id === $id || Auth::user()->type == 'admin'){
            return true;
        }
        return false;
    }

    public function coaches()
    {
        return $this->hasMany(Coach::class);
    }
}
