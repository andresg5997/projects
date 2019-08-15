<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = ['name', 'description', 'positions'];
    protected $casts = ['positions' => 'array'];

    public function teams(){
    	return $this->hasMany(Team::class);
    }

    public function lineups(){
        return $this->hasMany(LineUp::class);
    }
}
