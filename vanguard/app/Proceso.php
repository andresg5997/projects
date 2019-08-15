<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $fillable = ['nombre', 'descripcion'];

    public function estados()
    {
    	return $this->hasMany(Estado::class);
    }
}
