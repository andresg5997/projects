<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('location_id');
            $table->integer('user_id');
            $table->integer('type_id');
            $table->integer('sport_id');
            $table->integer('team_1');
            $table->integer('goals_1')->default(0);
            $table->string('team_2');
            $table->integer('goals_2')->default(0);
            $table->enum('uniform', [0,1])->default(0);
            $table->timestamp('date')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('events');
    }
}
