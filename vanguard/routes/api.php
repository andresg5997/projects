<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('estados', 'EstadosController@get')->name('api.estados');
Route::get('correos', 'CorreosController@get')->name('api.correos');
Route::get('estado/{estado}', 'EstadosController@getEstado')->name('api.estado');
Route::post('marcas/{marca}/subirArchivos', 'MarcasController@subirArchivos');
Route::get('usuarios/{id}/marcas', 'MarcasController@get')->name('api.marcas');
Route::get('marcas/{id}', 'MarcasController@getMarca')->name('api.marca');
Route::put('marcas/{id}', 'MarcasController@update')->name('api.marca.update');
Route::put('marcas/{marca}/update', 'MarcasController@updateValor')->name('api.marca.campo.update');
Route::get('paginate/marcas', 'MarcasController@paginate');
Route::get('search/marcas', 'MarcasController@search');
Route::get('procesos', 'ProcesosController@get')->name('api.procesos');
Route::get('procesos/{id}', 'ProcesosController@getProceso')->name('api.proceso');
Route::post('procesos', 'ProcesosController@store')->name('api.procesos.store');
Route::get('usuarios/{id}/tareas', 'TareasController@get')->name('api.usuarios.tareas');
Route::get('tareas/{id}', 'TareasController@getTarea')->name('api.tarea');
Route::get('transacciones', 'TransaccionesController@get')->name('api.transacciones');
Route::post('transacciones', 'TransaccionesController@store');
Route::put('usuarios/{id}', 'UsersController@update');
Route::delete('usuarios/{id}', 'UsersController@destroy');
Route::post('changePassword', 'UsersController@changePassword')->name('api.changePassword');
Route::get('usuarios/{id}/usuarios', 'UsersController@get')->name('api.usuario.usuarios');
Route::post('changeType', 'UsersController@changeType')->name('api.changeType');
Route::get('usuarios', 'UsersController@getAll')->name('api.usuarios');
Route::get('usuarios/{id}', 'UsersController@getUsuario')->name('api.usuario');
Route::put('updateAvatar', 'UsersController@updateAvatar')->name('api.updateAvatar');

Route::post('nuevocliente', 'Api\ApiController@store')->name('api.nuevoCliente'); 