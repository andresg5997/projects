<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function(Blueprint $table){
            $table->increments('id');
            $table->integer('team_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('teacher')->nullable();
            $table->string('gender')->nullable();
            $table->string('size')->nullable();
            $table->string('parent_name');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
