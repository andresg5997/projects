<?php

use App\Http\Controllers\dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['web', 'auth'])->group(function(){
    Route::get('/', 'HomeController@dashboard')->name('home');
    Route::get('config/dashboard', 'HomeController@configDashboard')->name('home.config');
    Route::get('config/adicionales', 'HomeController@configDatosAdicionales')->name('datosAdicionales.config');
    Route::get('config/correos', 'CorreosController@configCorreos')->name('correos.config');
    Route::post('marcas/datosAdicionales/', 'MarcasController@storeDatosAdicionales')->name('marcas.datos.config.store');
    Route::get('marcas/datosAdicionales/', 'MarcasController@getDatosAdicionales')->name('marcas.datos.config');
    Route::get('dashboard', 'HomeController@getDashboards')->name('dashboard.get');
    Route::post('dashboard', 'HomeController@storeDashboard')->name('dashboard.store');
    Route::delete('dashboard/{dashboard}', 'HomeController@destroyDashboard')->name('dashboard.destroy');
    Route::put('dashboard/{dashboard}', 'HomeController@updateDashboard')->name('dashboard.update');
    Route::resource('correos', 'CorreosController');
    Route::resource('marcas', 'MarcasController');
    Route::resource('usuarios', 'UsersController');
    Route::resource('tareas', 'TareasController');
    Route::resource('oposiciones', 'OposicionesController');
    Route::resource('transacciones', 'TransaccionesController');
    Route::resource('procesos', 'ProcesosController');
    Route::resource('estados', 'EstadosController');
    Route::post('procesos/{proceso}/cliente/{marca}', 'ProcesosController@procesoInit')->name('procesos.init');
    Route::get('transacciones/create/{tareaId}', 'TransaccionesController@create');
    Route::get('estados/create/procesos/{proceso}', 'ProcesosController@createEstado');
    Route::get('estados/{estado}/edit/procesos/{proceso}', 'ProcesosController@editEstado');
    Route::get('estado/{estado}', 'EstadosController@getEstado')->name('estado.get');
    Route::get('notificacion/readAll', 'NotificationsController@readAll')->name('notifications.readAll');
});

Route::get('pruebacorreo/{transaccion_id}/{correo_id}', 'CorreosController@enviarPlantilla');

Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');
Route::get('notificacion/{id}', 'NotificationsController@markAsRead')->name('notifications.show');
