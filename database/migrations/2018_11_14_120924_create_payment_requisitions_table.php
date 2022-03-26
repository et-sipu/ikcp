<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payment_requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requester_id');
            $table->string('released_to');
            $table->string('title');
            $table->text('details');
            $table->text('remarks')->nullable();
            $table->string('project')->nullable();
            $table->string('supplier');
            $table->string('last_pv_no')->nullable()->default(null);
            $table->decimal('last_payment_amount')->nullable()->default(null);
            $table->date('last_payment_date')->nullable()->default(null);
            $table->decimal('old_outstanding_invoices')->nullable()->default(null);
            $table->decimal('new_outstanding_invoices')->nullable()->default(null);
            $table->string('currency', 10)->default('MYR');
            $table->decimal('payment')->nullable()->default(null);
            $table->decimal('approved_payment')->nullable()->default(null);
            $table->string('cheque_no')->nullable()->default(null);
            $table->string('cheque_bank')->nullable()->default(null);
            $table->date('cheque_date')->nullable()->default(null);
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
        Schema::drop('payment_requisitions');
    }
}
