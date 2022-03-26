<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeQualificationInfosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_qualification_infos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('employee_id')->unsigned();
            $table->enum('type', ['Academic', 'Professional']);
            $table->string('authority');
            $table->string('degree');
            $table->string('year', 10);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('employee_qualification_infos');
    }
}
