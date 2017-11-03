<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('refunded_transaction_id');
            $table->string('transaction_id')->nullable();
            $table->string('status');
            $table->text('message');
            $table->json('raw_response');
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
        Schema::dropIfExists('refund_transactions');
    }
}
