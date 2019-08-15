<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['name', 'user_id', 'email', 'team_id', 'status', 'token'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public static function onHold(){
        return parent::where('status', 'hold')->get();
    }

    public static function accepted(){
        return parent::where('status', 'accepted')->get();
    }

    public static function rejected(){
        return parent::where('status', 'rejected')->get();
    }
}
