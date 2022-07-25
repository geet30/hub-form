<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('template_id')->nullable();
            // $table->unsignedInteger('completed_form_id')->nullable();
            $table->unsignedInteger('section_id')->nullable();
            $table->string('text')->nullable();
            $table->string('field')->nullable();
            $table->tinyInteger('question_type')->nullable();
            $table->text('type_option')->nullable();
            $table->tinyInteger('required')->default(0);
            $table->unsignedInteger('type')->nullable()->comment('1 for templates and 2 for completed forms');
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
        Schema::dropIfExists('questions');
    }
}
