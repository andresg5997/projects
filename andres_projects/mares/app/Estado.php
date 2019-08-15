<?php

namespace App;

use App\Tarea;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $fillable = ['nombre', 'estado_posterior', 'titulo_tarea', 'requisitos', 'tiempo_seguimiento'];

    public function subtareas()
    {
        return $this->hasOne(Subtareas::class, 'estado_id');
    }

    public function tareas($fecha_vencimiento = null)
    {
        $tareas = [];
        if(!count($this->subtareas)){
            return $tareas;
        }
        foreach($this->subtareas->data() as $tarea){
            if($fecha_vencimiento){
                $tarea += ['fecha_vencimiento' => $fecha_vencimiento];
            }
            $tareas[] = new Tarea($tarea);
        }
        return $tareas;
    }
}
