<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('group_admin_id')->nullable();
            $table->string('group_title')->nullable();
            $table->integer('members_count');
            $table->string('image')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('type', ['1 to one chat'=>1, 'group chat'=>2]);
            $table->enum('chat_status', ['profile delete'=>0, 'chat active'=>1]);
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
        Schema::dropIfExists('chat_rooms');
    }
}
