<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRebillTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rebill_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id');
            $table->string('reference')->nullable();
            $table->string('uuid');
            $table->float('total_amount');
            $table->string('currency');
            $table->string('transaction_type');
            $table->string('status');
            $table->text('message');
            $table->integer('code');
            $table->integer('web_id')->unsigned();
            $table->json('raw_response');

            $table->foreign('web_id')->references('id')->on('web_ids');
            $table->index('uuid');
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
        Schema::table('rebill_transactions', function (Blueprint $table) {
            $table->dropForeign(['web_id']);
        });

        Schema::dropIfExists('rebill_transactions');
    }
}
