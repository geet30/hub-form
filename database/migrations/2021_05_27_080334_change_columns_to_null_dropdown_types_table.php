<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsToNullDropdownTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dropdown_types', function (Blueprint $table) {

            $table->string('type_name')->nullable()->change();
            $table->integer('ques_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dropdown_types', function (Blueprint $table) {
            $table->dropColumn('type_name');
            $table->dropColumn('ques_id');
        });
    }
}
