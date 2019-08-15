<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('email')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('pais')->nullable();
            $table->string('nro_identificacion')->nullable();
            $table->string('direccion')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->timestamps();
            // Tablas anteriores ( solo para que no explote )
            $table->string('user_id')->nullable();
            $table->string('codigo')->nullable()->unique();
            $table->string('signo_distintivo')->nullable();
            $table->string('solicitante')->nullable();
            $table->string('clase')->nullable();
            $table->string('nro_incripcion')->nullable();
            $table->string('nro_registro')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->mediumtext('distincion_producto_servicio')->nullable();
            $table->string('lema_comercial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
