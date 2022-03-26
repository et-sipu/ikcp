<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCrewRequestIdColumnToSeafarerContractsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('seafarer_contracts', function (Blueprint $table) {
            $table->integer('crew_request_id')->unsigned()->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('seafarer_contracts', function (Blueprint $table) {
            $table->dropColumn('crew_request_id');
        });
    }
}
