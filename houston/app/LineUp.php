<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineUp extends Model
{
    protected $fillable = ['sport_id', 'event_id', 'user_id', 'line_up'];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sport(){
        return $this->belongsTo(Sport::class);
    }
}
