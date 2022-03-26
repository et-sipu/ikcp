<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cash_requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requester_id');
            $table->text('purpose');
            $table->boolean('cash_advance')->default(0);
            $table->boolean('reimbursement')->default(0);
            $table->boolean('deposit')->default(0);
            $table->decimal('amount');
            $table->decimal('approved_amount')->nullable()->default(null);
            $table->decimal('cash_advance_taken')->nullable()->default(null);
            $table->date('cash_advance_taken_date')->nullable()->default(null);
            $table->decimal('total_receipt_returned')->nullable()->default(null);
            $table->date('total_receipt_returned_date')->nullable()->default(null);
            $table->decimal('outstanding_ca')->nullable()->default(null);
            $table->string('status')->default('hod_approving');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('cash_requisitions');
    }
}
