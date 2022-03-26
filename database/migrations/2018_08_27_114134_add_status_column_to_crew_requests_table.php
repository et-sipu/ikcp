<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnToCrewRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('crew_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'done'])->default('pending')->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('crew_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
