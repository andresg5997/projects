<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availability';
    protected $fillable = ['player_id', 'event_id', 'status'];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function player(){
        return $this->belongsTo(Player::class);
    }
}
