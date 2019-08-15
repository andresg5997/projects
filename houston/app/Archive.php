<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archives';

    protected $fillable = ['name', 'path', 'team_id', 'extension', 'user_id'];

    public function team(){
        return $this->belongsTo('App\Team');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
