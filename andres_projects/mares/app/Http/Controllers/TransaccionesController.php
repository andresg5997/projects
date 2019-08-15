<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Archivo;
use App\Transaccion;
use Illuminate\Http\Request;
use App\Events\TransaccionRealizada;
use Illuminate\Support\Facades\Storage;

class TransaccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transacciones.index');
    }

    public function get()
    {
        $transacciones = Transaccion::with('marca', 'usuario', 'estado')->orderBy('fecha', 'DESC')->get();

        return response()
            ->json([
                'transacciones'         => $transacciones
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($tarea_id)
    {
        return view('transacciones.create', compact('tarea_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'requisitos' => 'array|min:1',
            'fecha_vencimiento' => 'required',
            'tarea_id'  => 'required',
            'user_id'   => 'required'
        ]);

        $datos = [];
        // dd($request->all());
        foreach($request->requisitos as $requisito){
            if($requisito['tipo'] === 'file'){
                if($requisito['valor'] == ''){
                    continue;
                }
                $exploded = explode(',', $requisito['valor']);
                $decoded = base64_decode($exploded[1]);
                $filename = $this->getFileName($requisito['valor']);
                Storage::put('/public/archivos/' . $filename, $decoded);
                Archivo::create([
                    'titulo' => $requisito['requisito'],
                    'nombre_archivo' => $filename,
                    'marca_id' => $request->marca_id,
                    'user_id' => $request->user_id
                ]);
                $datos[] = [
                    "requisito" => $requisito['requisito'],
                    "tipo" => $requisito['tipo'],
                    "valor" => $filename
                ];
            }elseif($requisito['tipo'] === 'date'){
                $datos[] = [
                    "requisito" => $requisito['requisito'],
                    "tipo" => $requisito['tipo'],
                    "valor" => $requisito['valor']['time']
                ];
            }
            else{
                $datos[] = [
                    "requisito" => $requisito['requisito'],
                    "tipo" => $requisito['tipo'],
                    "valor" => $requisito['valor']
                ];
            }
        }

        $transaccion = new Transaccion();
        $transaccion->user_id = $request->user_id;
        $transaccion->marca_id = $request->marca_id;
        $transaccion->estado_id = $request->estado_id;
        $transaccion->tarea_id = $request->tarea_id;
        $transaccion->fecha = date('Y-m-d');
        $transaccion->datos = json_encode($datos);
        $transaccion->save();

        Tarea::where('id', $request->tarea_id)->update(['status' => '1']);

        event(new TransaccionRealizada($transaccion, $request->estado_posterior, $request->asignar, $request->fecha_vencimiento['time']));

        return response()
            ->json([
                'saved' => true
            ]);
    }

    public function getFileName($base64)
    {
        $exploded = explode(',', $base64);
        if(str_contains($exploded[0], '/')){
            $extension = explode(';', explode('/', explode(',', $base64)[0])[1])[0];
            return str_random() . '.' . $extension;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaccion  $transaccion
     * @return \Illuminate\Http\Response
     */
    public function show(Transaccion $transaccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaccion  $transaccion
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaccion $transaccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaccion  $transaccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaccion $transaccion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaccion  $transaccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaccion $transaccion)
    {
        //
    }
}
