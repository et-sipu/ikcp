<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoiltedByColumnToVesselsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->integer('piloted_by')->unsigned()->nullable();
            $table->dropColumn('port_of_registry');
            $table->integer('port_of_registry_id')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->dropColumn('piloted_by');
            $table->dropColumn('port_of_registry_id');
            $table->string('port_of_registry');
        });
    }
}
