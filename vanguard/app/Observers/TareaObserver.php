<?php

namespace App\Observers;

use App\Tarea;
use App\Proceso;
use App\Estado;
use App\Correo;
use App\Transaccion;
use App\Events\TransaccionRealizada;
use App\Marca;

class TareaObserver
{
    /**
     * @param  \App\Tarea  $tarea
     * @return void
     */
    public function created(Tarea $tarea)
    {
        if($tarea->estado_id){
            $pass = true;
            $estado = Estado::find($tarea->estado_id);
            $requisitos = json_decode($estado->requisitos);
            foreach($requisitos as $requisito) {
                if($requisito->tipo != 'auto'){
                    $pass = false;
                } else {
                    $correo = Correo::find($requisito->opciones->tipo_tarea);
                    if($correo->correo_destino == 'tarea'){
                        $pass = false;
                    }
                }
            }
            if($pass){
                $datos = [];
                $hoy = date('Y-m-d');
                foreach($requisitos as $requisito){
                    if($requisito->opciones->tipo_fecha == 'fecha'){
                        $fecha = date('Y-m-d', strtotime($hoy . ' + ' . $requisito->opciones->fecha . ' days'));
                    } else {
                        $fecha = $hoy;
                    }
                    $correo = Correo::find($requisito->opciones->tipo_tarea);
                    if($correo->correo_destino == 'cliente'){
                        $cliente = Marca::find($tarea->marca_id);
                        $destino = $cliente->email;
                    } else {
                        $destino = $correo->correo_destino;
                    }
                    $valor = [
                        "tipo" => $requisito->opciones->tipo_tarea,
                        "fecha" => $fecha,
                        "destino" => $destino
                    ];
                    $datos[] = [
                        "requisito" => $requisito->nombre,
                        "tipo" => $requisito->tipo,
                        "valor" => $valor
                    ];
                }
                $transaccion = new Transaccion();
                $transaccion->user_id = $tarea->user_id;
                $transaccion->marca_id = $tarea->marca_id;
                $transaccion->estado_id = $tarea->estado_id;
                $transaccion->tarea_id = $tarea->tarea_id;
                $transaccion->fecha = date('Y-m-d');
                $transaccion->datos = json_encode($datos);
                $transaccion->save();
                $tarea->update(['status' => '1']);
                if($estado->estado_posterior){
                    $estado_posterior = $estado->estado_posterior;
                } else {
                    $estado_posterior = 0;
                }
                $asignar = $tarea->user_id;
                $fecha_vencimiento = date('Y-m-d', strtotime($hoy . ' + ' . $estado->tiempo_seguimiento . ' days'));
                event(new TransaccionRealizada($transaccion, $estado_posterior, $asignar, $fecha_vencimiento));
            }
        }
    }
}