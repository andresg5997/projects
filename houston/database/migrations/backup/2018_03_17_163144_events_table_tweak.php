<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventsTableTweak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('event_team');
        Schema::table('events', function (Blueprint $table) {
            $table->integer('team_id');
            $table->string('enemy_team')->nullable();
            $table->integer('goals_1')->nullable();
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
        //
    }
}
