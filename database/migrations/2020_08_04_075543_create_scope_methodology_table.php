<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScopeMethodologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scope_methodology', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('template_id');
            $table->unsignedInteger('completed_form_id')->nullable();
            $table->string('snm_name');
            $table->longText('snm_data')->nullable();
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
        Schema::dropIfExists('scope_methodology');
    }
}
