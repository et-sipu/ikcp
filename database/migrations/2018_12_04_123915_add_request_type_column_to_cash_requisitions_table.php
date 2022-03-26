<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestTypeColumnToCashRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cash_requisitions', function (Blueprint $table) {
            $table->string('request_type');
            $table->dropColumn('cash_advance');
            $table->dropColumn('reimbursement');
            $table->dropColumn('deposit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('cash_requisitions', function (Blueprint $table) {
            $table->dropColumn('request_type');
            $table->boolean('cash_advance')->default(0);
            $table->boolean('reimbursement')->default(0);
            $table->boolean('deposit')->default(0);
        });
    }
}
