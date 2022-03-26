<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveMediacalInfoToContract extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('seafarer_contracts', function (Blueprint $table) {
            $table->enum('duration_of_service_unit', ['Month', 'Week'])->after('duration_of_service')->default('Month');
            $table->string('currency')->after('basic_salary')->default('MYR');
            $table->enum('pay_frequency', ['Monthly', 'Daily'])->after('currency')->default('Monthly');
            $table->string('tested_by')->after('last_sign_off_date')->nullable();
            $table->string('insurance_type')->after('tested_by')->default('Marine P&I');
            $table->string('insurance_company')->after('insurance_type')->nullable();
            $table->date('insurance_expiry_date')->after('insurance_company')->nullable();
            $table->string('status')->after('insurance_expiry_date')->default('pending');
            $table->date('scheduled_sign_on_date')->after('pay_frequency');
            $table->dropColumn('last_ship_joined');
            $table->dropColumn('last_sign_off_date');
        });

        Schema::dropIfExists('employee_medical_infos');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('seafarer_contracts', function (Blueprint $table) {
            $table->dropColumn('duration_of_service_unit');
            $table->dropColumn('tested_by');
            $table->dropColumn('insurance_company');
            $table->dropColumn('insurance_type');
            $table->dropColumn('insurance_expiry_date');
            $table->dropColumn('currency');
            $table->dropColumn('pay_frequency');
            $table->dropColumn('status');
            $table->dropColumn('scheduled_sign_on_date');
            $table->string('last_ship_joined')->nullable();
            $table->date('last_sign_off_date')->nullable();
        });

        Schema::create('employee_medical_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->string('tested_by');
            $table->string('insurance_company');
            $table->string('insurance_type');
            $table->date('insurance_issue_date');
            $table->date('insurance_expiry_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
