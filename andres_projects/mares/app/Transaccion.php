<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
	protected $table = 'transacciones';
    protected $fillable = ['marca_id', 'tarea_id', 'user_id', 'estado_id', 'fecha', 'datos', 'observaciones'];

    protected $touches = ['marca'];

    protected $dates = ['fecha'];

    public function estado()
    {
    	return $this->belongsTo(Estado::class);
    }

    public function marca()
    {
    	return $this->belongsTo(Marca::class);
    }

    public function usuario()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function tarea()
    {
        return $this->belongsto(Tarea::class);
    }
}
