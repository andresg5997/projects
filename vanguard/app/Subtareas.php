<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtareas extends Model
{
    protected $table = 'estado_subtareas';

    protected $fillable = ['estado_id', 'data'];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function data()
    {
        return json_decode($this->data, true);
    }
}
