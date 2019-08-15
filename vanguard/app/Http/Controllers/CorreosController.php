<?php

namespace App\Http\Controllers;

use App\Marca;
use App\Correo;
use App\Estado;
use App\Proceso;
use App\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CorreosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        ///////////////////////
        // Vistas de Correos //
        ///////////////////////
        $directorios = Storage::disk('views')->files('correos');
        $vistas = [];
        foreach ($directorios as $directorio) {
            $exploded = explode('/', $directorio);
            $exploded_vista = explode('.', end($exploded));
            array_push($vistas, $exploded_vista[0]);
        }
        ////////////////////////
        // Lista de Variables //
        ////////////////////////
        $variables = [
            // Variables de cliente (0)
            [
                "Nombre completo" => "cliente.nombre",
                "Correo Electrónico" => "cliente.email",
                "Teléfono" => "cliente.telefono",
                "Ciudad" => "cliente.ciudad",
                "País" => "cliente.pais",
                "# Indentificación" => "cliente.nro_identificacion",
                "Dirección" => "cliente.direccion",
                "Fecha de Nacimiento" => "cliente.fecha_nacimiento"
            ],
            // Variables de estado (1)
            [
                "Nombre" => "estado.nombre",
                "Título de tarea principal" => "estado.titulo_tarea"
            ],
            // Variables de requisitos (2)
            [
                // <- Aqui se agregan los requisitos
            ]
        ];
        // Requisitos
        $procesos = Proceso::select('id', 'nombre')->distinct('id')->get();
        $estados = []; $procesos_array = []; $i = 0;
        foreach ($procesos as $proceso) {
            $procesos_array[$i] = $proceso;
            $estado_proceso = Estado::select('nombre', 'requisitos')->where('visible', '1')->where('proceso_id', $proceso->id)->get();
            array_push($estados, $estado_proceso);
            foreach ($estado_proceso as $estado_temporal) {
                $estado_temporal->requisitos = json_decode($estado_temporal->requisitos);
                if(isset($estado_temporal->requisitos)){
                    $requisitos_estado = [];
                    foreach ($estado_temporal->requisitos as $estado_temporal_requisitos) {
                        array_push($requisitos_estado, $estado_temporal_requisitos->nombre);
                    }
                }
                $estado_temporal->requisitos = $requisitos_estado;
            }
            $i++;
        }
        $j = 0;
        foreach ($estados as $proceso) {
            foreach ($proceso as $estado) {
                $variables[2][$procesos_array[$j]->nombre][$estado->nombre] = $estado->requisitos;
            }
            $j++;
        }
        // Datos Adicionales
        $datos_adicionales = DB::table('datos_adicionales')->where('cliente_id', '-1')->get();
        foreach ($datos_adicionales as $dato) {
            $valor_array = "datoAdicional." . $dato->nombre;
            $variables[0][$dato->nombre] = $valor_array;
        }
        return view('admin.config.correos.create', compact('vistas', 'variables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $correo = new Correo();
        $correo->id_plantilla = $request->id_plantilla;
        $correo->variables = json_encode($request->variables);
        if($request->correo_select == "blanco"){
            $correo->correo_destino = $request->correo_destino;
        } else {
            $correo->correo_destino = $request->correo_select;
        }
        $correo->save();
        return redirect()->route('correos.config');
    }

    public function configCorreos()
    {
        $correos = Correo::all();
        return view('admin.config.correos.index', compact('correos'));
    }

    public function enviarPlantilla($transaccion_id, $correo_id)
    {
        $datos['tipo']=$correo_id;
        $datos['fecha']=date('Y-m-d');
        $datos=(object)$datos;

        return enviarPlantilla($transaccion_id, $datos);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function getCorreo(Correo $correo)
    {

    }

    public function get()
    {
        $correos = Correo::all();
        return response()
            ->json([
                'correos' => $correos
            ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Correo $correo)
    {
        ///////////////////////
        // Vistas de Correos //
        ///////////////////////
        $directorios = Storage::disk('views')->files('correos');
        $vistas = [];
        foreach ($directorios as $directorio) {
            $exploded = explode('/', $directorio);
            $exploded_vista = explode('.', end($exploded));
            array_push($vistas, $exploded_vista[0]);
        }
        ////////////////////////
        // Lista de Variables //
        ////////////////////////
        $variables = [
            // Variables de cliente (0)
            [
                "Nombre completo" => "cliente.nombre",
                "Correo Electrónico" => "cliente.email",
                "Teléfono" => "cliente.telefono",
                "Ciudad" => "cliente.ciudad",
                "País" => "cliente.pais",
                "# Indentificación" => "cliente.nro_identificacion",
                "Dirección" => "cliente.direccion",
                "Fecha de Nacimiento" => "cliente.fecha_nacimiento"
            ],
            // Variables de estado (1)
            [
                "Nombre" => "estado.nombre",
                "Título de tarea principal" => "estado.titulo_tarea"
            ],
            // Variables de requisitos (2)
            [
                // <- Aqui se agregan los requisitos
            ]
        ];
        // Requisitos
        $procesos = Proceso::select('id', 'nombre')->distinct('id')->get();
        $estados = []; $procesos_array = []; $i = 0;
        foreach ($procesos as $proceso) {
            $procesos_array[$i] = $proceso;
            $estado_proceso = Estado::select('nombre', 'requisitos')->where('visible', '1')->where('proceso_id', $proceso->id)->get();
            array_push($estados, $estado_proceso);
            foreach ($estado_proceso as $estado_temporal) {
                $estado_temporal->requisitos = json_decode($estado_temporal->requisitos);
                if(isset($estado_temporal->requisitos)){
                    $requisitos_estado = [];
                    foreach ($estado_temporal->requisitos as $estado_temporal_requisitos) {
                        array_push($requisitos_estado, $estado_temporal_requisitos->nombre);
                    }
                }
                $estado_temporal->requisitos = $requisitos_estado;
            }
            $i++;
        }
        $j = 0;
        foreach ($estados as $proceso) {
            foreach ($proceso as $estado) {
                $variables[2][$procesos_array[$j]->nombre][$estado->nombre] = $estado->requisitos;
            }
            $j++;
        }
        // Datos Adicionales
        $datos_adicionales = DB::table('datos_adicionales')->where('cliente_id', '-1')->get();
        foreach ($datos_adicionales as $dato) {
            $valor_array = "datoAdicional." . $dato->nombre;
            $variables[0][$dato->nombre] = $valor_array;
        }
        // dd($variables);
        //////////////////////////////////////
        // Decodificar variables del correo //
        //////////////////////////////////////
        $correo->variables = json_decode($correo->variables);
        // dd($correo);
        return view('admin.config.correos.edit', compact('correo', 'vistas', 'variables'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     */
    public function update(Request $request, Correo $correo)
    {
        $correo->id_plantilla = $request->id_plantilla;
        $correo->variables = json_encode($request->variables);
        if($request->correo_select == "blanco"){
            $correo->correo_destino = $request->correo_destino;
        } else {
            $correo->correo_destino = $request->correo_select;
        }
        $correo->save();
        return redirect()->route('correos.config');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
