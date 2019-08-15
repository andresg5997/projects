<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmbedBooleanToAffiliateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affiliate_audio_views', function (Blueprint $table) {
            //
            $table->boolean('embed')->nullable();
        });

        Schema::table('affiliate_image_views', function (Blueprint $table) {
            //
            $table->boolean('embed')->nullable();
        });

        Schema::table('affiliate_video_views', function (Blueprint $table) {
            //
            $table->boolean('embed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affiliate_audio_views', function (Blueprint $table) {
            //
            $table->dropColumn('embed');
        });

        Schema::table('affiliate_image_views', function (Blueprint $table) {
            //
            $table->dropColumn('embed');
        });

        Schema::table('affiliate_video_views', function (Blueprint $table) {
            //
            $table->dropColumn('embed');
        });
    }
}
