<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('template_id');
            $table->unsignedInteger('completed_form_id')->nullable();
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('question_id')->nullable();
            $table->text('type_option')->nullable();
            $table->tinyInteger('required')->default(0);
            $table->longText('type')->nullable()->comment('1 for templates and 2 for completed forms');
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
        Schema::dropIfExists('answers');
    }
}
