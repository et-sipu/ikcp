<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSpouseInfosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_spouse_infos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('employee_id')->unsigned();
            $table->string('name', 100);
            $table->string('nric_no', 50);
            $table->string('occupation', 100)->nullable();
            $table->string('employer_name', 100)->nullable();
            $table->text('employer_address', 65535)->nullable();
            $table->string('hp', 50)->nullable();
            $table->date('dom')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('employee_spouse_infos');
    }
}
