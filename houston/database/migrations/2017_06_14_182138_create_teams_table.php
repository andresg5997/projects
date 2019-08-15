<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('user_id');
            $table->integer('sport_id');
            $table->string('timezone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->integer('zip')->nullable();
            $table->date('founded_at')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('team_player', function(Blueprint $table){
            $table->integer('team_id');
            $table->integer('player_id');
            $table->integer('number')->nullable();
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
        Schema::dropIfExists('teams');
        Schema::dropIfExists('team_player');
    }
}
