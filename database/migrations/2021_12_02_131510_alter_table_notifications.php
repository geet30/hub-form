<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->change();
            $table->bigInteger('from_user_id')->nullable()->change();
            $table->string('company_id')->nullable()->default(null);
            $table->string('i_ref_user_role_id')->nullable()->default(null);
            $table->string('i_ref_from_user_role_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('i_ref_user_role_id');
            $table->dropColumn('i_ref_from_user_role_id');
            $table->dropColumn('company_id'); 
        });
    }
}
