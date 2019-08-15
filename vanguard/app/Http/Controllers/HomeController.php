<?php

namespace App\Http\Controllers;

use App\User;
use App\Marca;
use App\Estado;
use App\Proceso;
use App\Dashboard;
use App\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

  protected $nombre_estado;

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    return view('home');
  }

  public function getDashboards()
  {
    $dashboards = Dashboard::all();
    foreach ($dashboards as $dashboard) {
      $dashboard->estado = Estado::find($dashboard->estado_id);
    }
    return response()
      ->json([
        'dashboards' => $dashboards
      ]);
  }

  public function dashboard(){
    //////////////////////////
    // Paneles de Dashboard //
    //////////////////////////
    $dashboards = Dashboard::all();
    $transacciones_fecha = []; $ids_clientes = []; $i = 0;
    // Se crean los arrays segun el numero de dashboards
    foreach ($dashboards as $dashboard) {
      $dashboard->estado = Estado::find($dashboard->estado_id);
      $ids_clientes[$i] = [];
      $transacciones_fecha[$i] = [];
      $i++;
    }
    $i = 0;
    // Se llenan los arrays con los ids de los clientes que se van a mostrar en el dashboard principal
    foreach ($dashboards as $dashboard) {
      $fecha_estado = date('Y-m-d', strtotime("-$dashboard->dias_estado days"));
      $transacciones = Transaccion::whereDate('created_at', '>=', $fecha_estado)->where('estado_id', $dashboard->estado_id)->get();
      foreach ($transacciones as $transaccion) {
        array_push($transacciones_fecha[$i], date('Y-m-d', strtotime($transaccion->created_at)));
        array_push($ids_clientes[$i], Marca::find($transaccion->marca_id));
      }
      $i++;
    }
    $dashboardClientes = collect($ids_clientes);
    $fechas = collect($transacciones_fecha);
    ////////////
    // Tareas //
    ////////////
    $tareas_temporal = User::find(Auth::id())->tareas()->with('archivos', 'marca', 'transaccion')->orderBy('fecha_vencimiento', 'asc')->get();
    $tareas = []; $tarea_estado = [];
    foreach ($tareas_temporal as $index => $tarea) {
      if($tarea->status == '0'){
        $now = strtotime(date('d F Y'));
        if(!$tarea->fecha_vencimiento){
          $tarea->fecha_vencimiento = date('U');
        }
        $tareaTime = strtotime($tarea->fecha_vencimiento->format('d F Y'));
        if(($now == $tareaTime) || ($now < $tareaTime)){
          if($now == $tareaTime) {
            $tarea->estado = 'Para hoy';
          } else {
            $tarea->estado = 'Pendiente';
          }
        } else {
          $tarea->estado = 'Vencida';
        }
        array_push($tareas, $tarea);
      }
    }
    $hoy = date('Y-m-d');
    $tareasObj = collect($tareas);
    return view('dashboard', compact('tareasObj', 'tareas', 'dashboards', 'dashboardClientes', 'fechas', 'hoy'));
  }

  public function configDashboard()
  {
    $dashboards = Dashboard::get();
    $procesos = Proceso::get();
    $estados = Estado::where('visible', '1')->get();
    foreach ($dashboards as $dashboard) {
      $dashboard->estado = Estado::find($dashboard->estado_id);
    }
    return view('admin.config.dashboard', compact('dashboards', 'procesos', 'estados'));
  }

  public function configDatosAdicionales()
  {
    return view('admin.config.datosAdicionales');
  }

  public function storeDashboard(Request $request)
  {
    $dashboard = new Dashboard();
    $dashboard->estado_id = $request->estado_id;
    $dashboard->dias_estado = $request->dias_estado;
    $dashboard->save();
    return response()
      ->json([
        'saved' => true,
        'dashboard' => $dashboard
      ]);
  }

  public function destroyDashboard(Dashboard $dashboard)
  {
    $dashboard->delete();
    return response()
      ->json([
        'saved' => true
      ]);
  }

  public function updateDashboard(Dashboard $dashboard, Request $request)
  {
    $dashboard->dias_estado = $request->dias_estado;
    $dashboard->estado_id = $request->estado_id;
    $dashboard->save();
    $dashboard->estado = Estado::find($request->estado_id);
    return response()
      ->json([
        'updated' => true,
        'dashboard' => $dashboard
      ]);
    }
}
