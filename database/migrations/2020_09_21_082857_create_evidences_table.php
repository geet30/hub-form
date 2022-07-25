<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('action_id')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_type')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('assined_user_id')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('evidences');
    }
}
