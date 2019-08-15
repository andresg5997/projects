<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateImageViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_image_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id');
            $table->integer('owner_id');
            $table->integer('user_id');
            $table->string('country');
            $table->tinyInteger('country_group')->default(4);
            $table->string('state');
            $table->string('city');
            $table->ipAddress('ip');
            $table->boolean('adblock')->default(0);
            $table->decimal('commission', 15, 10)->default(0);

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
        Schema::dropIfExists('affiliate_image_views');
    }
}
