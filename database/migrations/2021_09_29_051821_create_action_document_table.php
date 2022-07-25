<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('action_id')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_type')->nullable()->comment('1 for image and 2 for audio and 3 for pdf 4 for video 5 for document');;
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
        Schema::dropIfExists('action_document');
    }
}
