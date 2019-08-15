<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = ['titulo', 'descripcion', 'status', 'estado_id', 'user_id', 'asignado', 'marca_id', 'fecha_vencimiento'];

    protected $dates = ['fecha_vencimiento'];

    public function usuario()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function asignadoA()
    {
    	return $this->belongsTo(User::class, 'asignado');
    }

    public function marca(){
    	return $this->belongsTo(Marca::class);
    }

    public function archivos(){
        return $this->hasMany(Archivo::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function transaccion()
    {
        return $this->hasOne(Transaccion::class);
    }
}
