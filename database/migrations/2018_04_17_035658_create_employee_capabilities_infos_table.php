<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeCapabilitiesInfosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_capabilities_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->string('rank');
            $table->string('coc_certificate_type')->nullable();
            $table->string('coc_certificate_class')->nullable();
            $table->jsonb('security_course')->nullable();
            $table->jsonb('other_courses')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('employee_capabilities_infos');
    }
}
