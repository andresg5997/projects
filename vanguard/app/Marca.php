<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'clientes';

    protected $fillable = ['nombre', 'apellido', 'codigo', 'signo_distintivo', 'solicitante', 'clase', 'nro_incripcion', 'nro_registro', 'fecha_vencimiento', 'distincion_producto_servicio', 'lema_comercial', 'user_id'];

    protected $dates = ['created_at', 'fecha_vencimiento', 'updated_at'];

    public function estado()
    {
        if($transaccion = $this->transacciones()->orderBy('created_at', 'DESC')->first()){
            $this->estado = $transaccion->estado->nombre;
            $this->estado_id = $transaccion->estado_id;
            $this->ultima_actualizacion = $transaccion->created_at;
            return;
        }
    }

    public function tareas()
    {
    	return $this->hasMany(Tarea::class);
    }

    public function transacciones(){
    	return $this->hasMany(Transaccion::class);
    }

    public function archivos(){
        return $this->hasMany(Archivo::class);
    }
}
