<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string ('registration');
            $table->string ('registration_no');
            $table->string ('company');
            $table->enum('type', ['New','Renewal']);
            $table->string ('grade');
            $table->date ('validity_from');
            $table->date('validity_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_registrations');
    }
}
