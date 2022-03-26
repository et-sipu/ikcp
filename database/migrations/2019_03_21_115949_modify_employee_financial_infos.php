<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyEmployeeFinancialInfos extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('employee_financial_infos', function (Blueprint $table) {
            $table->string('income_tax_no')->nullable();
            $table->string('epf')->nullable();
            $table->string('socso_no')->nullable();
            $table->string('zakat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('employee_financial_infos', function (Blueprint $table) {
            $table->dropColumn('income_tax_no');
            $table->dropColumn('epf');
            $table->dropColumn('socso_no');
            $table->dropColumn('zakat');
        });
    }
}
