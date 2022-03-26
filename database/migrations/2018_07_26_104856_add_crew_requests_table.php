<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCrewRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('crew_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vessel_id')->unsigned();
            $table->integer('replacement_of_id')->unsigned()->nullable();
            $table->string('rank')->nullable();
            $table->date('to_sign_on')->nullable();
            $table->integer('suggested_seafarer_id')->unsigned()->nullable();
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('crew_requests');
    }
}
