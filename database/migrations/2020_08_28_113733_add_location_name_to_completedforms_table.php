<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationNameToCompletedformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('completed_forms', function (Blueprint $table) {
            $table->string('location_name');
            $table->string('selected_date');
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
            $table->dropColumn('location_name');
            $table->dropColumn('selected_date');
        });
    }
}
