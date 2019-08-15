<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTareaAutomaticaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarea_automatica', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plantilla');
            $table->string('datos');
            $table->string('destino');
            $table->string('fecha_envio');
            $table->enum('enviado', ['0', '1'])->default('0');
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
        Schema::dropIfExists('tarea_automatica');
    }
}
