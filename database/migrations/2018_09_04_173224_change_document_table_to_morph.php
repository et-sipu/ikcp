<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDocumentTableToMorph extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::rename('employee_documents', 'documents');

        Schema::table('documents', function (Blueprint $table) {
            $table->string('documentable_type')->after('id')->default('Seafarer');
            $table->renameColumn('employee_id', 'documentable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::rename('documents', 'employee_documents');

        Schema::table('employee_documents', function (Blueprint $table) {
            $table->dropColumn('documentable_type');
            $table->renameColumn('documentable_id', 'employee_id');
        });
    }
}
