<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('template_id')->nullable();
            $table->unsignedInteger('completed_form_id')->nullable();
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('question_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('descriptions')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('assined_user_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('business_unit_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('status')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('type')->nullable()->comment('1 for templates and 2 for completed forms');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
