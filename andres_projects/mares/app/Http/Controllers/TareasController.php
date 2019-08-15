<?php

namespace App\Http\Controllers;

use App\Archivo;
use App\Events\TareaAsignada;
use App\Events\TareaAsignadaRealizada;
use App\Tarea;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TareasController extends Controller
{

    public function __construct()
    {
        //
    }

    public function index()
    {
        return view('tareas.index');
    }

    public function get($user_id, Request $request){
        // Se debe mejorar esto para poder combinar todas las tareas entre asignadas y propias en el orden adecuado: fecha_vencimiento ASC
        $tareas = User::find($user_id)->tareas()->with('archivos', 'marca', 'transaccion')->get();
        return response()
            ->json([
                'tareas' => $tareas
            ]);
    }

    public function getTarea($id)
    {
        $tarea = Tarea::with('marca', 'asignadoA', 'estado', 'usuario', 'transaccion')->find($id);
        if($tarea->transaccion){
            $tarea->transaccion->datos = json_decode($tarea->transaccion->datos, true);
        }

        return response()
            ->json([
                'tarea' => $tarea
            ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo'            => 'required|max:255',
            'descripcion'       => 'required|max:3000',
            'fecha_vencimiento' => 'required',
            'archivos'          => 'array'
        ]);

        $tarea = new Tarea($request->except('archivos', 'fecha_vencimiento'));
        $tarea->fecha_vencimiento = $request->fecha_vencimiento['time'];
        $tarea->save();

        $datos = $this->uploadArchivos($request->archivos, $tarea);

        $request->user()->archivos()->saveMany($datos['archivos']);

        $tarea->archivos;
        $tarea->marca;
        $tarea->asignadoA;

        if($tarea->asignadoA){
            event(new TareaAsignada($tarea));
        }

        return response()
            ->json([
                'saved' => true,
                'tarea' => $tarea,
                'fileErrors' => $datos['fileErrors']
            ]);
    }

    public function getFileName($base64)
    {
        $exploded = explode(',', $base64);
        if(str_contains($exploded[0], '/')){
            $extension = explode(';', explode('/', explode(',', $base64)[0])[1])[0];
            return date('Y') . '/' . str_random() . '.' . $extension;
        }
        return false;
    }

    public function uploadArchivos($archivos, $tarea)
    {
        $array = [];
        $fileErrors = 0;
        foreach($archivos as $archivo){
            $parts = explode(',', $archivo['archivo']);
            $decoded = base64_decode($parts[1]);
            $fileName = $this->getFileName($archivo['archivo']);

            if($fileName !== false){
                Storage::put("public/archivos/" . $fileName, $decoded);
                $array[] = new Archivo([
                    'titulo'            => $archivo['titulo'],
                    'nombre_archivo'    => $fileName,
                    'tarea_id'          => $tarea->id,
                    'marca_id'          => ($request->marca_id != 0) ? $request->marca_id : null
                ]);
            }else{
                $fileErrors++;
            }
        }
        return ['archivos' => $array, 'fileErrors' => $fileErrors];
    }

    public function show(Tarea $tarea)
    {
        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea)
    {
        //
    }

    public function update(Request $request, Tarea $tarea)
    {
        $tarea = $request->user()->tareas()->findOrFail($tarea->id);

        $tarea->update($request->all());

        // if($tarea->asignado === $request->user()->id ){
        //     event(new TareaAsignadaRealizada($tarea));
        // }

        return response()
            ->json([
                'updated' => true,
                'tarea' => $tarea
            ]);
    }

    public function destroy(Tarea $tarea)
    {
        //
    }
}
