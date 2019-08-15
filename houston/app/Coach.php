<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $table = 'coaches';
    protected $fillable = ['team_id', 'order', 'title', 'name', 'email', 'phone'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
