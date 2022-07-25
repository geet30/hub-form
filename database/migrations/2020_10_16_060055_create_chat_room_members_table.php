<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRoomMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_room_members', function (Blueprint $table) {
            $table->id();
            $table->integer('chat_room_id');
            $table->integer('member_id');
            $table->timestamp('joined_at');
            $table->enum('status', ['left_the_group'=>0, 'alive'=>1]);
            $table->enum('is_active', ['inactive'=>0, 'active'=>1]);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_room_members');
    }
}
