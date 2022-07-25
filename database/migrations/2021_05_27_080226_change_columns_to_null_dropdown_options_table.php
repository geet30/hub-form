<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsToNullDropdownOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dropdown_options', function (Blueprint $table) {

            $table->string('option_name')->nullable()->change();
            $table->integer('type_id')->nullable()->change();
            $table->string('color_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dropdown_options', function (Blueprint $table) {
            $table->dropColumn('option_name');
            $table->dropColumn('type_id');
            $table->dropColumn('color_code');
        });
    }
}
