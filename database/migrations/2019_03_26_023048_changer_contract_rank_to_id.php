<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangerContractRankToId extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('seafarer_contracts', function (Blueprint $table) {
            $table->integer('contract_rank_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('seafarer_contracts', function (Blueprint $table) {
            $table->dropColumn('contract_rank_id');
        });
    }
}
