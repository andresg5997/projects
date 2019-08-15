<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marcas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('nombre');
            $table->string('codigo')->nullable()->unique();
            $table->string('signo_distintivo')->nullable();
            $table->string('solicitante')->nullable();
            $table->string('clase')->nullable();
            $table->string('nro_incripcion')->nullable();
            $table->string('nro_registro')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->mediumtext('distincion_producto_servicio')->nullable();
            $table->string('lema_comercial')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marcas');
    }
}
