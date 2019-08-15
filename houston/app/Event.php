<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = ['id', 'name', 'location_name', 'location_url', 'user_id', 'type_id', 'sport_id', 'uniform', 'date', 'end_date', 'notes', 'season', 'goals_1', 'goals_2', 'team_id', 'enemy_team'];

    public function teams(){
    	return $this->belongsTo(Team::class);
    }

    public function assignments(){
        return $this->hasMany(Assignment::class);
    }

    public function availability(){
        return $this->hasMany(Availability::class);
    }

    public function lineups(){
        return $this->hasMany(LineUp::class);
    }

    public function type()
    {
        return $this->belongsTo(EventType::class, 'type_id');
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
