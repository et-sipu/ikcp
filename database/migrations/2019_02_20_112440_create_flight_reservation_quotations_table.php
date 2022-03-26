<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightReservationQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('flight_reservation_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->time('flight_time');
            $table->string('airlines');
            $table->string('website');
            $table->decimal('price');
            $table->integer('flight_reservation_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('flight_reservation_quotations');
    }
}
