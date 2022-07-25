<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionIdToChatRoomMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_room_members', function (Blueprint $table) {
            $table->integer('action_id')->after('chat_room_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_room_members', function (Blueprint $table) {
            $table->dropColumn('action_id');
        });
    }
}
