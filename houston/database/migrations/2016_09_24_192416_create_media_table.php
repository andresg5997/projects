<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('approved')->default(0);
            $table->string('slug')->unique();
            $table->integer('category_id')->unsigned()->default(0);
            $table->integer('user_id')->unsigned()->default(1);
            $table->integer('views')->unsigned()->default(0);
            $table->string('type');
            $table->string('title', 255)->index();
            $table->text('body')->nullable();
            $table->text('upload_session');
            $table->boolean('private')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('media');
    }
}
