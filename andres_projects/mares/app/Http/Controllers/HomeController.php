<?php

namespace App\Http\Controllers;

use App\Marca;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public $dias_negadas = 15;
    public $dias_publicacion = 30;
    public $dias_concedidas = 30;
    public $dias_devueltas = 30;

    protected $nombre_estado;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function dashboard(){
        $marcas_negadas = $this->getMarcas($this->dias_negadas, 'Oferta rechazada');
        $marcas_publicacion = $this->getMarcas($this->dias_publicacion, 'Publicación en prensa');
        $marcas_concedidas = $this->getMarcas($this->dias_concedidas, 'Marca concedida');
        $marcas_devueltas = $this->getMarcas($this->dias_devueltas, 'Subsanar la solicitud');

        $dias = [
            'dias_negadas' => $this->dias_negadas,
            'dias_publicacion' => $this->dias_publicacion,
            'dias_concedidas' => $this->dias_concedidas,
            'dias_devueltas' => $this->dias_devueltas
        ];

        $porVencer = Marca::whereDate('fecha_vencimiento', '<=', date('Y-m-d', strtotime('+6 months')))
        ->whereDate('fecha_vencimiento', '>', date('Y-m-d'))
        ->get();

        return view('dashboard',
            compact('marcas_negadas', 'marcas_publicacion', 'marcas_concedidas', 'marcas_devueltas', 'dias', 'porVencer')
        );
    }

    public function getMarcas($dias, $nombre_estado)
    {
        $this->nombre_estado = $nombre_estado;
        $marcas = Marca::whereDate('updated_at', '>', date('Y-m-d', strtotime("-$dias days")))->get();
        foreach($marcas as $marca){
            $marca->estado();
        }
        return $marcas->filter(function($value, $key){
            return $value->estado === $this->nombre_estado;
        });
    }
}
