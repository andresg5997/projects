<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'apellido', 'email', 'password', 'type', 'telefono', 'cargo', 'departamento', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tareasPropias()
    {
        return $this->hasMany(Tarea::class);
    }

    public function tareasAsignadas()
    {
        return $this->hasMany(Tarea::class, 'asignado');
    }

    public function tareas(){
        return $this->tareasPropias()->union($this->tareasAsignadas()->getQuery());
    }

    // Dejó de funcionar por PHP Fatal Error: Maximum function nesting level reached: '256'
    // public function tareas(){
    // }

    public function fullName(){
        return "$this->nombre $this->apellido";
    }

    public function archivos(){
        return $this->hasMany(Archivo::class);
    }

    public function routeNotificationForMail() {
        return $this->email;
    }
}
