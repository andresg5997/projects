<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['player_id', 'event_id', 'details'];

    public function player(){
        return $this->belongsTo(Player::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }
}
