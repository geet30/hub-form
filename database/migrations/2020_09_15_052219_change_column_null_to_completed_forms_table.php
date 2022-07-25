<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnNullToCompletedFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('completed_forms', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->change();
            $table->string('user_name')->nullable()->change();
            $table->integer('company_id')->nullable()->change();
            $table->string('company_name')->nullable()->change();
            $table->integer('business_unit_id')->nullable()->change();
            $table->string('business_unit_name')->nullable()->change();
            $table->integer('department_id')->nullable()->change();
            $table->string('department_name')->nullable()->change();
            $table->integer('project_id')->nullable()->change();
            $table->string('project_name')->nullable()->change();
            $table->string('selected_date')->nullable()->change();
            $table->string('location_name')->nullable()->change();
            $table->integer('status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('completed_forms', function (Blueprint $table) {
            //
        });
    }
}
