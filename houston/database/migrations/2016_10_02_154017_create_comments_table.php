<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('approved')->default(1);
            //$table->integer('parent_id');

            $table->integer('user_id')->unsigned();
            //$table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');

            $table->integer('media_id')->unsigned();
            //$table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');

            //$table->string('name')->nullable();
            //$table->string('email')->nullable();
            //$table->string('url')->nullable();
            $table->text('body');

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
        Schema::dropIfExists('comments');
    }
}
