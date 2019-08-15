<?php

namespace App\Http\Controllers;

use App\Estado;
use App\Subtareas;
use Illuminate\Http\Request;

class EstadosController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->except('getEstado', 'get');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.estados.index');
    }

    public function getEstado(Estado $estado)
    {
        $this->estado_id = $estado->id;
        $estado_temporal = Estado::having('estado_posterior', 'LIKE', '%'.$estado->id.'%')->get();
        $estado_anterior = $estado_temporal->map(function ($anterior){
            $pass = false;
            $ids = explode(',', $anterior->estado_posterior);
            foreach($ids as $id){
                if((int)$id === $this->estado_id){
                    $pass = true;
                }
            }
            if($pass){
                return $anterior;
            }
        });
        $estado->requisitos = json_decode($estado->requisitos, true);
        if(!$estado->requisitos){
            $estado->requisitos = [];
        }
        $estado->tareas = $estado->tareas();
        unset($estado->subtareas, $estado->created_at, $estado->updated_at);
        return response()
            ->json([
                'estado' => $estado,
                'estado_anterior' => $estado_anterior
            ]);
    }

    public function get()
    {
        $estados = Estado::with('subtareas')->where('visible', '=', '1')->get();
        $estados->map(function($estado){
            $estado->requisitos = json_decode($estado->requisitos, true);
            // Ahora traemos los estados posteriores
            $posteriores = [];
            if($estado->estado_posterior && strpos($estado->estado_posterior, ',') === -1){
                $posteriores[] = Estado::find($estado->estado_posterior);
            }elseif($estado->estado_posterior){
                $ids = explode(',', $estado->estado_posterior);
                foreach($ids as $id){
                    $posteriores[] = Estado::find($id);
                }
            }
            $estado->posteriores = $posteriores;
            $estado->tareas = $estado->tareas();
            unset($posteriores);
            return $estado;
        });

        return response()
            ->json([
                'estados' => $estados
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.estados.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd(json_encode($request->all()));
        // $this->validate($request,[
        //     'nombre' => 'required|min:4',
        //     'estado_posterior' => 'array',
        //     'estado_posterior.*.id' => 'exists:estados',
        //     'requisitos' => 'array|min:1',
        //     'requisitos.*.nombre' => 'required',
        //     'requisitos.*.tipo' => 'required',
        //     'tareas' => 'array',
        //     'tareas.*.titulo' => 'required|min:4'
        // ]);
        $estado = new Estado(['nombre' => $request->nombre]);
        // Traemos sólo los ids de los estados posteriores y los separamos por coma
        $estado->estado_posterior = implode(',', array_column($request->estado_posterior, 'id'));

        // Convertimos los requisitos en array para codificar
        // en JSON:
        // $requisitos = [];
        // foreach($request->requisitos as $requisito){
        //     if($requisito['tipo'] == 'select'){
        //         $requisitos[] = [$requisito['nombre'] => $requisito['tipo'], 'opciones' => $requisito['opciones']];
        //     }else{
        //         $requisitos[] = [$requisito['nombre'] => $requisito['tipo']];
        //     }
        // }
        // dd(json_encode($requisitos));
        $estado->titulo_tarea = $request->titulo_tarea;
        $estado->tiempo_seguimiento = $request->tiempo_seguimiento;

        $requisitos = $request->requisitos;
        for ($i=0; $i < count($requisitos) ; $i++) {
            if($requisitos[$i]['tipo'] == "auto"){
                $requisitos[$i]['opciones']['tipo_fecha'] = $requisitos[$i]['opciones']['tipo_fecha']['tipo'];
                $requisitos[$i]['opciones']['tipo_tarea'] = $requisitos[$i]['opciones']['tipo_tarea']['id'];
            }
        }
        $estado->requisitos = json_encode($requisitos);
        // $estado->requisitos = json_encode($request->requisitos);
        $estado->proceso_id = $request->proceso['id'];
        $estado->save();

        // Guardamos las tareas correspondientes al estado
        // $subtarea = new Subtareas(['estado_id' => $estado->id]);
        // Codificamos las tareas en JSON
        // $subtarea->data = json_encode($request->tareas);
        // $subtarea->save();

        return response()
            ->json([
                'saved' => true
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function show(Estado $estado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function edit(Estado $estado)
    {
        return view('admin.estados.edit', compact('estado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estado $estado)
    {
        $this->validate($request,[
            'nombre' => 'required|min:4',
            'estado_posterior' => 'array',
            'estado_posterior.*.id' => 'exists:estados',
            'requisitos' => 'array|min:1',
            'requisitos.*.nombre' => 'required',
            'requisitos.*.tipo' => 'required'
        ]);
        $estado->nombre = $request->nombre;
        $estado->estado_posterior = implode(',', array_column($request->estado_posterior, 'id'));
        $estado->titulo_tarea = $request->titulo_tarea;
        $estado->tiempo_seguimiento = $request->tiempo_seguimiento;
        $requisitos = $request->requisitos;
        for ($i=0; $i < count($requisitos) ; $i++) {
            if($requisitos[$i]['tipo'] == "auto"){
                $requisitos[$i]['opciones']['tipo_fecha'] = $requisitos[$i]['opciones']['tipo_fecha']['tipo'];
                $requisitos[$i]['opciones']['tipo_tarea'] = $requisitos[$i]['opciones']['tipo_tarea']['id'];
            }
        }
        $estado->requisitos = json_encode($requisitos);
        // dd($estado);
        $estado->save();

        return response()
            ->json([
                'updated' => true
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estado $estado)
    {
        $estado->visible = '0';
        $estado->save();
        return response()
            ->json([
                'deleted' => true
            ]);
    }
}
