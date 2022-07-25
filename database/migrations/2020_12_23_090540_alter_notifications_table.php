<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('from_user_id');
            $table->string('title', 155)->nullable()->change();
            $table->string('message', 255)->nullable();
            $table->bigInteger('notificationable_id')->nullable();
            $table->string('notificationable_type', 100)->nullable();
            $table->boolean('status')->default(0)->comment('1 for read and 0 for unread')->change();
            $table->dropColumn('action_id');
            $table->dropColumn('receiver_id');
            $table->softDeletes();
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
            Schema::dropIfExists('notifications');
        });
    }
}
