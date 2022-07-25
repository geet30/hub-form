<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectedOptionDropdownTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dropdown_types', function (Blueprint $table) {
            $table->integer('selected_type')->default(0)->after('type_name');
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
            $table->dropColumn('selected_type');
        });
    }
}
