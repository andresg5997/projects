<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('usuarios/{id}/marcas', 'MarcasController@get')->name('api.marcas');
Route::get('marcas/{id}', 'MarcasController@getMarca')->name('api.marca');
Route::put('marcas/{id}', 'MarcasController@update');
Route::get('usuarios/{id}/usuarios', 'UsersController@get')->name('api.usuario.usuarios');
Route::get('usuarios/{id}/tareas', 'TareasController@get')->name('api.usuarios.tareas');
Route::put('usuarios/{id}', 'UsersController@update');
Route::delete('usuarios/{id}', 'UsersController@destroy');
Route::post('changePassword', 'UsersController@changePassword')->name('api.changePassword');
Route::post('changeType', 'UsersController@changeType')->name('api.changeType');
Route::get('estado/{estado}', 'EstadosController@getEstado')->name('api.estado');
Route::get('usuarios', 'UsersController@getAll')->name('api.usuarios');
Route::put('updateAvatar', 'UsersController@updateAvatar')->name('api.updateAvatar');
Route::get('transacciones', 'TransaccionesController@get')->name('api.transacciones');
Route::post('transacciones', 'TransaccionesController@store');
Route::get('usuarios/{id}', 'UsersController@getUsuario')->name('api.usuario');
Route::get('tareas/{id}', 'TareasController@getTarea')->name('api.tarea');
Route::get('estados', 'EstadosController@get')->name('api.estados');
Route::post('marcas/{marca}/subirArchivos', 'MarcasController@subirArchivos');

Route::get('paginate/marcas', 'MarcasController@paginate');
Route::get('search/marcas', 'MarcasController@search');