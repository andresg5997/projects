<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('username')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('token')->unique()->nullable();
            $table->enum('confirmed', ['0', '1'])->default('0');
            $table->decimal('all_time_account_balance', 15, 10)->default(0);
            $table->decimal('current_account_balance', 15, 10)->default(0);
            $table->decimal('commissions_audio', 15, 10)->default(0);
            $table->decimal('commissions_image', 15, 10)->default(0);
            $table->decimal('commissions_video', 15, 10)->default(0);
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('blocked_on')->nullable();
            $table->boolean('notification_followers_add_media')->default(1);
            $table->boolean('notification_following')->default(1);
            $table->boolean('notification_comments')->default(1);

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
        Schema::dropIfExists('users');
    }
}
