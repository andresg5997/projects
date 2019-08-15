<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Marca;
use App\Events\MarcaCreada;
use App\Events\ProcesoIniciado;
use App\Estado;
use App\Proceso;
use Illuminate\Http\RedirectResponse;


class ApiController extends Controller
{
   

 public function store(Request $request)
  {



  $marca = new Marca();
    $marca->nombre = $request->nombre;
    $marca->apellido = $request->apellido;
    $marca->email = $request->email;
    $marca->telefono = $request->telefono;
    $marca->ciudad = $request->ciudad;
    $marca->pais = $request->pais;
    $marca->nro_identificacion = $request->nro_identificacion;
    $marca->direccion = $request->direccion;
    $marca->fecha_nacimiento = $request->fecha_nacimiento;
      $marca->user_id = '1';

   // $marca->user_id = $request->user()->id;

    $marca->save();
     

    event(new MarcaCreada($marca));


if ($request->proceso) {
	$estado = Estado::where('visible', '1')->where('proceso_id', $request->proceso)->orderBy('id', 'asc')->first();
        event(new ProcesoIniciado($estado, $marca));
} else {
	# code...
}


  

   // return response()
    //  ->json([
//        'saved' => true,200
       
  //    ]);

 return  new RedirectResponse("https://autodealerhouston.com/vanguard/"); 
  }





}
