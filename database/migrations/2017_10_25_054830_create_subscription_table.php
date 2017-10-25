<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('web_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('trial')->default(true);
            $table->boolean('active')->default(true);
            $table->string('bill_token');
            $table->boolean('waitlist')->default(false);
            $table->dateTime('last_charge_date')->nullable();
            $table->dateTime('next_charge_date')->nullable();
            $table->timestamps();

            $table->foreign('web_id')->references('id')->on('web_ids');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['web_id']);
        });

        Schema::dropIfExists('user_subscriptions');
    }
}
