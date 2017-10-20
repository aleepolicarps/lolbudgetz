<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_ids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_id');
            $table->string('currency');
            $table->float('amount');
            $table->float('trial_amount');
            $table->smallInteger('billing_period');
            $table->smallInteger('trial_period');
            $table->string('product_id');
            $table->string('trial_product_id');
            $table->string('return_url');
            $table->json('variables');
            $table->string('locale');
            $table->integer('unsubscribe_delay');
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
        Schema::dropIfExists('web_ids');
    }
}
