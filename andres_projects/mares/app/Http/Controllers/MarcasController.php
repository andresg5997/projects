<?php

namespace App\Http\Controllers;

use App\Marca;
use App\Archivo;
use App\Events\MarcaCreada;
use App\Events\MarcaEditada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Middleware\Auth as AuthMiddleware;

class MarcasController extends Controller
{

  public $pagination = 5;
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('marcas.index', compact('marcas'));
  }

  public function get(){
    $marcas = Marca::all();
    $marcas->map(function($marca){
      $marca->estado();
    });

    return response()
    ->json([
      'marcas' => $marcas
    ]);
  }

  public function create()
  {
    // nro_inscripcion
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'nombre' => 'required|max:255',
      'signo_distintivo' => 'required'
    ]);
    $exploded = explode(',', $request->signo_distintivo);
    $fileName = str_random();
    $decoded = base64_decode($exploded[1]);
    if(str_contains($exploded[0], 'jpeg')){
      $extension = 'jpg';
    }else{
      $extension = 'png';
    }

    $fileName = str_random() . '.' . $extension;
    Storage::put('/public/marcas/' . $fileName, $decoded);

    $marca = new Marca($request->except('signo_distintivo', 'fecha_vencimiento'));
    $marca->signo_distintivo = $fileName;
    $marca->fecha_vencimiento = $request->fecha_vencimiento['time'];
    $marca->user_id = $request->user()->id;
    $marca->save();
    $marca->estado();

    event(new MarcaCreada($marca));

    return response()
      ->json([
        'saved' => true,
        'marca' => $marca
      ]);
  }

  public function show(Marca $marca)
  {
    return view('marcas.show', compact('marca'));
  }

  public function getMarca($id){
    $marca = Marca::with('tareas', 'tareas.asignadoA', 'tareas.usuario', 'archivos', 'archivos.tarea', 'archivos.usuario', 'transacciones', 'transacciones.usuario', 'transacciones.estado')->find($id);
    $marca->estado();
    $marca->transacciones->map(function($transaccion){
      $transaccion->datos = json_decode($transaccion->datos);
      if(count($transaccion->datos) == 0){
        $transaccion->datos = [];
      }
      return $transaccion;
    });
    return response()
      ->json([
        'marca' => $marca
      ]);
  }

  public function edit(Marca $marca)
  {
    //
  }

  public function update($id, Request $request)
  {
    // Recordar eliminar el signo distintivo antes de guardar el nuevo
    $marca = Marca::findOrFail($id);
    $this->validate($request, [
      'lema_comercial'                =>  'max:255',
      'solicitante'                   =>  'max:255',
      'codigo'                        =>  'max:255',
      'clase'                         =>  'max:255',
      'nro_inscripcion'               =>  'max:255',
      'nro_registro'                  =>  'max:255',
      'distincion_producto_servicio'  =>  'max:3000'
    ]);
    $marca->update($request->except('fecha_vencimiento') + ['fecha_vencimiento' => $request->fecha_vencimiento['time']]);
    event(new MarcaEditada($marca));

    return response()
    ->json([
      'updated' => true,
      'marca'   => $marca
    ]);
  }

  public function destroy(Marca $marca)
  {
    //
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

  public function subirArchivos(Marca $marca, Request $request)
  {
    $datos = $this->uploadArchivos($request->archivos, $request->user_id);
    $marca->archivos()->saveMany($datos['archivos']);
    return response()
      ->json([
        'uploaded' => true,
        'total' => count($datos['archivos']),
        'failed' => $datos['failed']
      ]);
  }

  public function uploadArchivos($archivos, $user_id)
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
          'user_id'           => $user_id
        ]);
      }else{
        $fileErrors++;
      }
    }
    return ['archivos' => $array, 'failed' => $fileErrors];
  }

  public function paginate(){
    $marcas = Marca::paginate($this->pagination);

    return response()
      ->json([
        'marcas' => $marcas
      ]);
  }

  public function search(Request $request){
    if($request->fecha_start == null && $request->fecha_end == null){
      $marcas = Marca::where($request->col_1, 'LIKE', "%$request->valor_1%")
      ->where($request->col_2, 'LIKE', "%$request->valor_2%")
      ->paginate($this->pagination);
    } else {
      if($request->fecha_start == null){
        $request->fecha_start = Marca::min($request->col_3);
      }
      if($request->fecha_end == null){
        $request->fecha_end = Marca::max($request->col_3);
      }
      $marcas = Marca::where($request->col_1, 'LIKE', "%$request->valor_1%")
      ->where($request->col_2, 'LIKE', "%$request->valor_2%")
      ->whereDate($request->col_3, '>=', $request->fecha_start)
      ->whereDate($request->col_3, '<=', $request->fecha_end)
      ->paginate($this->pagination);
    }
    return response()
      ->json([
        'marcas' => $marcas
      ]);
  }
}
