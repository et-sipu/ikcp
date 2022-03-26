<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeafarerContractsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('seafarer_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seafarer_id')->unsigned();
            $table->integer('vessel_id')->unsigned();
            $table->integer('duration_of_service');
            $table->decimal('basic_salary');
            $table->date('sign_on_date');
            $table->date('sign_off_date')->nullable();
            $table->integer('sign_on_port_id')->unsigned();
            $table->integer('pay_leave');
            $table->string('last_ship_joined')->nullable();
            $table->date('last_sign_off_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seafarer_contracts');
    }
}
