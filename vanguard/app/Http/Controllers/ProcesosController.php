<?php

namespace App\Http\Controllers;

use App\Estado;
use App\Proceso;
use App\Marca;
use Illuminate\Http\Request;
use App\Events\ProcesoIniciado;

class ProcesosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.procesos.index');
    }

    public function get()
    {
        $procesos = Proceso::with('estados')->get();
        return response()
            ->json([
                'procesos' => $procesos
            ]);
    }

    public function getProceso($id)
    {
        $proceso = Proceso::find($id);
        return response()
            ->json([
                'proceso' => $proceso
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function createEstado(Proceso $proceso)
    {
        return view('admin.estados.create', compact('proceso'));
    }

    public function procesoInit(Proceso $proceso, Marca $marca)
    {
        $estado = Estado::where('visible', '1')->where('proceso_id', $proceso->id)->orderBy('id', 'asc')->first();
        event(new ProcesoIniciado($estado, $marca));
        return response()
            ->json([
                'saved' => true
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
          'nombre' => 'required|max:255'
        ]);
        $proceso = new Proceso($request->except('csrf_token'));
        $proceso->save();
        return response()
            ->json([
                'proceso'   => $proceso,
                'saved'     => true
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.procesos.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editEstado(Estado $estado, Proceso $proceso)
    {
        return view('admin.estados.edit', compact('estado', 'proceso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
