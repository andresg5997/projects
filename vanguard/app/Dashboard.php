<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $table = 'dashboard';

    protected $fillable = ['panel', 'fecha_vencimiento_estado', 'estado'];

    protected $dates = ['fecha_vencimiento_estado'];

}
