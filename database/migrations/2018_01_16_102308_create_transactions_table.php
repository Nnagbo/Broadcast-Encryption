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
            $table->string('debtor_name');
            $table->string('debtor_email');
            $table->bigInteger('phone');
            $table->string('description');
            $table->string('date');
            $table->bigInteger('amount');
            $table->integer('status')->default(0);
            $table->string('paydate');
            $table->bigInteger('loaner_id');
            $table->string('proof');
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
