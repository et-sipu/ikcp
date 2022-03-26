<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyEmployeeContactInfos extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('employee_contact_infos', function (Blueprint $table) {
            $table->string('personal_email')->nullable();
            $table->text('next_of_kin_address')->nullable();
            $table->string('next_of_kin_relationship')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('employee_contact_infos', function (Blueprint $table) {
            $table->dropColumn('personal_email');
            $table->dropColumn('next_of_kin_address');
            $table->dropColumn('next_of_kin_relationship');
        });
    }
}
