<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('question_id')->nullable();
            $table->string('notes')->nullable();
            $table->unsignedInteger('document_id')->nullable();
            // $table->string('document_name')->nullable();
            $table->unsignedInteger('type')->nullable()->comment('1 for templates and 2 for completed forms');
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
        Schema::dropIfExists('guides');
    }
}
