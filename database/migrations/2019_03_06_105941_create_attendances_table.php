<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->date('attendance_date');
            $table->enum('dept_movement', ['A', 'P', 'MC', 'AL', 'ET', 'OB']);
            $table->time('logged_in_time')->nullable();
            $table->time('logged_out_time')->nullable();
            $table->text('hr_review')->nullable();
            $table->text('ey_review')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('attendances');
    }
}
