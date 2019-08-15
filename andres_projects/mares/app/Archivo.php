<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $fillable = ['titulo', 'nombre_archivo', 'tarea_id', 'marca_id', 'user_id'];

    public function tarea(){
        return $this->belongsTo(Tarea::class);
    }

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
