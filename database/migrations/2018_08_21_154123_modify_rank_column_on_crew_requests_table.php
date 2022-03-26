<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRankColumnOnCrewRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('crew_requests', function (Blueprint $table) {
            $table->dropColumn('rank');
            $table->integer('rank_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('crew_requests', function (Blueprint $table) {
            $table->string('rank');
            $table->dropColumn('rank_id');
        });
    }
}
