<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('template_type', ['SMS', 'ISM', 'Training', 'Reports']);
            $table->string('code')->default(null)->nullable();
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('doc_templates');
    }
}
