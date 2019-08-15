<?php

namespace App\Http\Controllers;

use App\Marca;
use Spatie\PdfToText\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Atomescrochus\StringSimilarities\Compare;

class OposicionesController extends Controller
{

    private $marcas;
    private $minimo_nombre = 50;
    private $minimo_distincion = 50;

    public function index()
    {
        return view('oposiciones.index');
    }

    public function store(Request $request)
    {
        $this->minimo_nombre =  $request->minimo_nombre;
        $this->minimo_distincion = $request->minimo_distincion;
        $file = $request->file('pdf');
        $filename = Storage::put('public/oposiciones', $file);
        // $filename = 'public/oposiciones/rpze97eDbHRLjKnvv8Pa9fyf4XNjGDSZDiB2rmm2.pdf';
        $text = Pdf::getText(storage_path('app/' . $filename));
        Storage::delete($filename);
        $separator = '_______________________________________________________________________________';

        if(strpos($text, $separator) === false){
            return view('oposiciones.index')->with('error', 'El archivo no está en el formato esperado.');
        }

        // Datos que debemos buscar
        $datos_parte = ['Insc.', 'SOLICITADA POR:', 'PARA DISTINGUIR:', 'Clase ', 'TRAMITANTE:'];
        $datos_completos = ['Insc.', 'NOMBRE DE LA MARCA:', 'SOLICITADA POR:', 'PARA DISTINGUIR:', 'Clase ', 'TRAMITANTE:'];

        // Buscamos el primer separador para eliminar los titulares del documento
        $position = stripos($text, $separator);
        // y cortamos el archivo desde allí en adelante.
        $marcas_string = substr($text, $position);

        // Separamos cada marca por la cadena separadora del documento
        $marcas_array = explode($separator, $marcas_string);
        // Nota: Algunas de estas cadenas tienen un dato llamado
        // "NOMBRE DE LA MARCA" y otros no.

        // Como el archivo retorna un primer array como cadena vacía, lo eliminamos.
        array_shift($marcas_array);

        // Guardamos todas las marcas en un array
        $marcas = [];

        foreach($marcas_array as $marca_string){
            // Se eliminan todos los saltos de línea y se crea una string plana
            $marca_string = str_replace("\n", '', $marca_string);

            // Validar si la cadena tiene el dato NOMBRE DE LA MARCA
            if(strpos($marca_string, $datos_completos[1]) !== false){
                $datos = $datos_completos;
            } else {
                $datos = $datos_parte;
            }

            // Se separan los valores con un |
            for($i = 0; $i < count($datos); $i++){
                if(strpos($marca_string, $datos[$i]) !== false){
                    $marca_string = str_replace($datos[$i], '|', $marca_string);
                }else{
                    $marca_string = str_replace('PARADISTINGUIR:', '|', $marca_string);
                }
            }

            $marca_array = explode('|', $marca_string);

            array_shift($marca_array); // Se elimina el primer array, que está vacío

            if(count($marca_array) === 6){
                array_splice($marca_array, 2, 1);
            }

            foreach($datos as $key => $dato){
                if(isset($marca_array[$key])){
                    // $marca[$this->remove_colon_lower($dato)] = $marca_array[$key];
                    $marca[] = $marca_array[$key];
                }
            }

            // Se añade al array de marcas
            $marcas[] = $marca;
            unset($marca);
        }
        $marcas = $this->getSimilar($marcas);

        return view('oposiciones.index', compact('marcas'))
                    ->with('minimo_nombre', $this->minimo_nombre)
                    ->with('minimo_distincion', $this->minimo_distincion);
    }

    public function remove_colon_lower(string $string)
    {
        return trim(strtolower(str_replace(':', '', $string)));
    }

    public function getSimilar($marcas)
    {
        // dd($marcas);
        $this->marcas = $marcas;
        $this->models = Marca::all();
        $models = $this->models->map(function ($model){
            $comparador = new Compare();
            $model->similar = collect();
            foreach($this->marcas as $marca){
                $marca[1] = trim(explode('Domicilio', $marca[1])[0]);

                if($marca[1] != '' || $model->nombre != ''){
                    $porcentaje_nombre = $comparador->smg(strtoupper($model->nombre), strtoupper($marca[1]));
                }else{
                    $porcentaje_nombre = 0;
                }
                // Calculamos la diferencia en el para distinguir
                if($marca[2] != '' || $model->distincion_producto_servicio != ''){
                    $porcentaje_distincion = $comparador
                        ->smg(
                            strtoupper($model->distincion_producto_servicio),
                            strtoupper($marca[2]));
                }else{
                    $porcentaje_distincion = 0;
                }

                // Como existe un margen de error en cada marca, vamos a corregirlo con una función
                // $marca = $this->swap_distinguir_clase($marca);

                if($porcentaje_nombre >= ($this->minimo_nombre / 100) || $porcentaje_distincion >= ($this->minimo_distincion / 100))
                {
                    $model->similar->push(['porcentaje_nombre' => $porcentaje_nombre, 'porcentaje_distincion' => $porcentaje_distincion, 'marca' => $marca]);
                }
            }
            return $model;
        });
        return $models;
    }

    public function swap_distinguir_clase(array $marca)
    {
        if(strlen($marca['para distinguir']) <= 2 && strlen($marca['clase']) > 2){
            $marca['para distinguir'] = $marca['clase'];
        }
        return $marca;
    }
}
