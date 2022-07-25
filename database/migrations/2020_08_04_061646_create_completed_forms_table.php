<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompletedFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('save_as_id')->nullable(); //USE WHILE SAVING AS A EXISTING COMPLETED FORM
            $table->string('form_id')->nullable();
            $table->string('title')->nullable();
            $table->bigInteger('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates');
            $table->integer('user_id');
            $table->string('user_name');
            $table->integer('company_id');
            $table->string('company_name');
            $table->integer('business_unit_id');
            $table->string('business_unit_name');
            $table->integer('department_id');
            $table->string('department_name');
            $table->integer('project_id');
            $table->string('project_name');
            $table->integer('location_id');
            $table->integer('status');
            $table->softDeletes();
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
        Schema::dropIfExists('completed_forms');
    }
}
