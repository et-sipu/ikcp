<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignonsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('signons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seafarer_contract_id')->unsigned();
            $table->date('sign_date');
            $table->enum('sign_type', ['ON', 'OFF']);
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('signons');
    }
}
