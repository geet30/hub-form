<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableToChatRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->renameColumn('action_id', 'type_id');
            $table->char('type_model', 10)->nullable();
            $table->bigInteger('question_id')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->renameColumn('type_id', 'action_id');
            $table->dropColumn(['type_model', 'question_id']);
        });
    }
}