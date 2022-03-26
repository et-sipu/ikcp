<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('race')->nullable();
            $table->string('marital_status')->nullable();
            $table->enum('blood_type', ['A-', 'A+', 'B-', 'B+', 'AB-', 'AB+', 'O-', 'O+'])->nullable();
            $table->integer('is_seafarer')->default(0);
            $table->integer('user_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('race');
            $table->dropColumn('marital_status');
            $table->dropColumn('blood_type');
            $table->dropColumn('user_id');
            $table->integer('is_seafarer')->default(1);
        });
    }
}
