<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAffiliateTrackingToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referred_by')->nullable();
            $table->string('affiliate_id')->unique()->nullable();
            $table->decimal('all_time_referral_balance', 15, 10)->default(0);
            $table->decimal('current_referral_balance', 15, 10)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referred_by');
            $table->dropColumn('affiliate_id');
            $table->dropColumn('all_time_referral_balance');
            $table->dropColumn('current_referral_balance');
        });
    }
}
