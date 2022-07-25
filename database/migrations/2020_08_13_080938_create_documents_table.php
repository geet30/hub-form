<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->integer('save_as_id')->nullable(); //USE WHILE SAVING AS A EXISTING COMPLETED FORM
            $table->string('form_id')->nullable();
            $table->string('title')->nullable();
            $table->integer('folder_id')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_type')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('template_id')->nullable();
            $table->integer('user_id')->nullable();
            // $table->string('user_name')->nullable();
            $table->integer('company_id')->nullable();
            // $table->string('company_name')->nullable();
            $table->integer('business_unit_id')->nullable();
            // $table->string('business_unit_name')->nullable();
            $table->integer('department_id')->nullable();
            // $table->string('department_name')->nullable();
            $table->integer('project_id')->nullable();
            // $table->string('project_name')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('share_with_supplier')->nullable();
            $table->integer('Use_in_mobile')->nullable();
            $table->text('description')->nullable();
            $table->date('expires_at')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
