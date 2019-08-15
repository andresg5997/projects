<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEventTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_team', function(Blueprint $table){
            $table->increments('id');
            $table->integer('event_id');
            $table->integer('team_id');
            $table->integer('goals_1')->nullable();
            $table->string('team_2')->nullable();
            $table->integer('goals_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_team');
    }
}
