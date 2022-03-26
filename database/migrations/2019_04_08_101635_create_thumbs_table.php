<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThumbsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('thumbs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('employee_id')->unsigned();
            $table->string('staff_id', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('thumbs');
    }
}
