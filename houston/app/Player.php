<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
	protected $table = 'players';
    protected $fillable =
    ['team_id', 'first_name', 'last_name', 'number', 'email', 'phone', 'teacher', 'gender', 'size', 'parent_name'];
    // protected $hidden = ['password'];
    // protected $guarded = ['id'];
    //

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->parent_last_name;
    }

    public function getParentAttribute()
    {
        return $this->parent_name;
    }

    public function team(){
    	return $this->belongsTo(Team::class);
    }

    public function events(){
    	return $this->hasMany(Event::class);
    }

    public function assignments(){
        return $this->hasMany(Assignment::class);
    }

    public function availability(){
        return $this->hasMany(Availability::class);
    }
}
