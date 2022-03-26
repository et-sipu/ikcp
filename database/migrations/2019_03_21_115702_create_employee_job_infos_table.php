<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeJobInfosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_job_infos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('employee_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->integer('designation_id')->unsigned()->nullable();
            $table->integer('branch_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('employee_job_infos');
    }
}
