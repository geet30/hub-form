<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('documents', function (Blueprint $table) {
            $table->string('i_ref_user_role_id')->nullable()->default(null);
            $table->string('i_ref_owner_role_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('i_ref_user_role_id');
            $table->dropColumn('i_ref_owner_role_id');
        });
    }
}
