<?php

namespace App\Http\Controllers;

use App\Marca;
use App\Archivo;
use App\Events\MarcaCreada;
use App\Events\MarcaEditada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

  public function getDatosAdicionales()
  {
    $datosAdicionales = DB::table('datos_adicionales')->get();
    return response()
    ->json([
      'datosAdicionales' => $datosAdicionales
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
      'email' => 'sometimes|nullable|email|max:255',
      'telefono' => 'max:15',
      'direccion' => 'max:255'
    ]);
    $marca = new Marca();
    $marca->nombre = $request->nombre;
    $marca->apellido = $request->apellido;
    $marca->email = $request->email;
    $marca->telefono = $request->telefono;
    $marca->ciudad = $request->ciudad;
    $marca->pais = $request->pais;
    $marca->nro_identificacion = $request->nro_identificacion;
    $marca->direccion = $request->direccion;
    $marca->fecha_nacimiento = $request->fecha_nacimiento['time'];
    $marca->user_id = $request->user()->id;
    $marca->save();
    foreach($request->datosAdicionales as $dato) {
      DB::table('datos_adicionales')->insert([
        'categoria'  => $dato['categoria'],
        'nombre'      => $dato['nombre'],
        'valor'       => $dato['valor'],
        'cliente_id'   => $marca->id
      ]);
    }

    event(new MarcaCreada($marca));

    return response()
      ->json([
        'saved' => true,
        'marca' => $marca
      ]);
  }

  public function storeDatosAdicionales(Request $request)
  {
    DB::table('datos_adicionales')->where('cliente_id', -1)->delete();
    foreach ($request->all() as $dato) {
      DB::table('datos_adicionales')->insert([
        'cliente_id' => -1,
        'categoria'  => $dato['categoria'],
        'nombre'     => $dato['nombre'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
    }
    return response()
      ->json([
        'updated' => true
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
      'apellido'                      =>  'max:255',
      'email' => 'sometimes|nullable|email|max:255',
      'ciudad'                        =>  'max:255',
      'pais'                          =>  'max:255',
      'nro_identificacion'            =>  'max:255',
      'direccion'                     =>  'max:255'
    ]);
    $marca->nombre = $request->nombre;
    $marca->apellido = $request->apellido;
    $marca->email = $request->email;
    $marca->telefono = $request->telefono;
    $marca->ciudad = $request->ciudad;
    $marca->pais = $request->pais;
    $marca->nro_identificacion = $request->nro_identificacion;
    $marca->direccion = $request->direccion;
    $marca->fecha_nacimiento = $request->fecha_nacimiento['time'];
    $marca->save();
    $table = [];
    foreach($request->datosAdicionales as $dato) {
      $table = DB::table('datos_adicionales')->where([
        ['cliente_id', $marca->id],
        ['categoria', $dato['categoria']],
        ['nombre', $dato['nombre']]
      ])->get();
      if($table->isEmpty()){
        DB::table('datos_adicionales')->insert([
          'categoria'  => $dato['categoria'],
          'nombre'      => $dato['nombre'],
          'valor'       => $dato['valor'],
          'cliente_id'   => $marca->id,
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s')
        ]);
      } else {
        DB::table('datos_adicionales')->where([
          ['cliente_id', $marca->id],
          ['categoria', $dato['categoria']],
          ['nombre', $dato['nombre']]
        ])->update([
          'valor'      => $dato['valor'],
          'updated_at' => date('Y-m-d H:i:s')
        ]);
      }
    }
    event(new MarcaEditada($marca));

    return response()
    ->json([
      'updated' => true,
      'marca'   => $marca
    ]);
  }

  public function updateValor(Marca $marca, Request $request)
  {
    foreach ($request->all() as $campo => $valor) {
      if(!is_null($valor)){
        $marca->update([$campo => $valor]);
      }
    }
    return response()
      ->json([
        'updated' => true
      ]);
  }

  public function destroy(Marca $marca)
  {
    $marca->delete();
    return response()
      ->json([
        'deleted' => true
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
