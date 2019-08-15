<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
    //
    protected $fillable = ['user_id', 'follower_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
