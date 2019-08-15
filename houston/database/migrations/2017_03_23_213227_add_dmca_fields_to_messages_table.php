<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDmcaFieldsToMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            //
            $table->string('legal_name')->nullable();
            $table->string('rightsholder_legal_name')->nullable();
            $table->string('rightsholder_country')->nullable();
            $table->string('address')->nullable();
            $table->string('company')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('job_title')->nullable();
            $table->string('city')->nullable();
            $table->longText('infringing_urls')->nullable();
            $table->boolean('owner')->nullable();
            $table->boolean('agent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            //
            $table->dropColumn('legal_name');
            $table->dropColumn('rightsholder_legal_name');
            $table->dropColumn('rightsholder_country');
            $table->dropColumn('address');
            $table->dropColumn('company');
            $table->dropColumn('zip');
            $table->dropColumn('phone');
            $table->dropColumn('country');
            $table->dropColumn('job_title');
            $table->dropColumn('city');
            $table->dropColumn('infringing_urls');
            $table->dropColumn('owner');
        });
    }
}
