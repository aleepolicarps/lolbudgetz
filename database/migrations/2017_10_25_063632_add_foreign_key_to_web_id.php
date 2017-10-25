<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToWebId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->integer('web_id')->unsigned()->change();
            $table->foreign('web_id')->references('id')->on('web_ids');
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
            $table->dropForeign(['web_id']);
        });
    }
}
