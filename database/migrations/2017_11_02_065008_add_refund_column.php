<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->boolean('refunded')->default(false);
        });

        Schema::table('rebill_transactions', function (Blueprint $table) {
            $table->boolean('refunded')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->dropColumn('refunded');
        });

        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->dropColumn('refunded');
        });
    }
}
