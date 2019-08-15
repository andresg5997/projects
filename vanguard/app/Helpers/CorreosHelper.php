<?php

use App\Marca;
use App\Correo;
use App\Estado;
use App\Transaccion;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
/**
* Enviar correo de tarea automatizada
*
* @param $transaccion_id
* @param $correo_id
*/
function enviarPlantilla($transaccion_id, $datos)
{
 
    $transaccion = Transaccion::find($transaccion_id);
    
    $correo = Correo::find($datos->tipo);
    $variables = json_decode($correo->variables);
    $data = [];
    foreach ($variables as $variable) {
        $exploded = explode('.', $variable);
        if($exploded[0] == 'cliente'){
            $dato_marca = Marca::select($exploded[1])->where('id',$transaccion->marca_id)->value($exploded[1]);
            $variable = str_replace(' ', '_', $variable);
            $variable = str_replace('.', '_', $variable);
//            json_decode($dato_marca);
//dato_marca->toJson();
         $data[$variable] = $dato_marca;
        }elseif($exploded[0] == 'estado'){
            $dato_estado = Estado::select($exploded[1])->find($transaccion->estado_id)->value($exploded[1]);
            $variable = str_replace(' ', '_', $variable);
            $variable = str_replace('.', '_', $variable);
            $data[$variable] = $dato_estado;
        }else{
            $requisitos_estado = Transaccion::select('datos')
            ->orderBy('id', 'desc')
            ->where('estado_id', $exploded[0])
            ->where('marca_id', $transaccion->marca_id)
            ->first();
           
 $requisitos_estado = $requisitos_estado['datos'];
          $requisitos_estado = json_decode($requisitos_estado);
        // dd($requisitos_estado);   
            foreach ($requisitos_estado as $requisito) {
                if($requisito->requisito == $exploded[1])
                {
                    if($requisito->tipo == 'map'){
                    // Se deberia mandar el link de gmap
                       $requisito_valor = $requisito->valor;
                        } else {
                        $requisito_valor = $requisito->valor;
                     }
                    $variable = str_replace(' ', '_', $variable);
                    $variable = str_replace('.', '_', $variable);
                    $data[$variable] = $requisito_valor;
                }
            }
        }
    }
    if($correo->correo_destino == 'cliente'){
        $correo_destino = DB::table('clientes')->find($transaccion->marca_id)->email;
     } elseif($correo->correo_destino == 'tarea'){
        $correo_destino = $datos->correo_destino;
    } else {
        $correo_destino = $correo->correo_destino;
    }
    DB::table('tarea_automatica')->insert([
        'plantilla'    => 'correos.' . $correo->id_plantilla,
        'datos'        => json_encode($data),
        'destino'      => $correo_destino,
        'fecha_envio'  => date('Y-n-j',strtotime($datos->fecha)),
        'created_at'   => date('Y-m-d H:i:s'),
        'updated_at'   => date('Y-m-d H:i:s')
    ]);
}
