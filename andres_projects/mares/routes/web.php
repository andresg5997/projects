<?php

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
	Route::resource('marcas', 'MarcasController');
	Route::resource('usuarios', 'UsersController');
	Route::resource('tareas', 'TareasController');
	Route::resource('oposiciones', 'OposicionesController');
    Route::resource('transacciones', 'TransaccionesController');
    Route::get('transacciones/create/{tareaId}', 'TransaccionesController@create');
    Route::resource('estados', 'EstadosController');
    Route::get('notificacion/readAll', 'NotificationsController@readAll')->name('notifications.readAll');
});

Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');
Route::get('notificacion/{id}', 'NotificationsController@markAsRead')->name('notifications.show');
