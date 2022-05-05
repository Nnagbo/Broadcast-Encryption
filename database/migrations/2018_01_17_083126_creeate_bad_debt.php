<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreeateBadDebt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baddebt', function (Blueprint $table) {
            $table->increments('id');
            $table->string('debtor_name');
            $table->string('debtor_email');
            $table->string('debtor_phone');
            $table->bigInteger('amount');
            $table->string('proof');
            $table->bigInteger('loaner_id');
            $table->string('paydate');
            $table->integer('status')->default(0);
            $table->string('dispute_id');
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
        Schema::dropIfExists('baddebt');
    }
}
