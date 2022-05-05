<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisputeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispute', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dispute_id');
            $table->string('message');
            $table->string('sender_email');
            $table->string('reciever_email');
            $table->string('date');
            $table->bigInteger('bad_debt_id');
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
        Schema::dropIfExists('dispute');
    }
}
