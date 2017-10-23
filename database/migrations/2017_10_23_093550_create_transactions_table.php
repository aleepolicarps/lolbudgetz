<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id');
            $table->float('total_amount');
            $table->string('converted_amount')->nullable();
            $table->string('currency');
            $table->string('transaction_type');
            $table->string('status');
            $table->text('message');
            $table->string('code');
            $table->string('bill_token');
            $table->string('uuid');
            $table->integer('web_id');
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
        Schema::dropIfExists('transactions');
    }
}
