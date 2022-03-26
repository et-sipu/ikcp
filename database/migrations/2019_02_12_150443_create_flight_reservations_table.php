<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightReservationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('flight_reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requester_id');
            $table->integer('payment_requisition_id')->default(null);
            $table->enum('form_type', ['VESSEL', 'SITE']);
            $table->string('form_target');
            $table->enum('flight_type', ['RETURN', 'ONEWAY']);
            $table->date('departure_date');
            $table->enum('departure_period', ['AM', 'PM']);
            $table->string('departure_from');
            $table->string('departure_to');
            $table->integer('departure_luggage');
            $table->date('return_date')->default(null);
            $table->enum('return_period', ['AM', 'PM'])->default(null);
            $table->string('return_from')->default(null);
            $table->string('return_to')->default(null);
            $table->integer('return_luggage')->default(null);
            $table->enum('transport_type', ['BUS', 'CAB', 'TRAIN', 'FERRY'])->default(null);
            $table->string('transport_from')->default(null);
            $table->string('transport_to')->default(null);
            $table->decimal('transportation_cost');
            $table->text('purpose');
            $table->string('status');
            $table->decimal('price')->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('flight_reservations');
    }
}
