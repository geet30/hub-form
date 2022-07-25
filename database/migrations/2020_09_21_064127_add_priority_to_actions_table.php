<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriorityToActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('actions', function (Blueprint $table) {
        $table->integer('reocurring_actions')->nullable()->after('location_id');
        $table->integer('priority')->nullable()->after('status');
        $table->integer('evidence_id')->nullable()->after('due_date');
        $table->longText('comments')->nullable()->after('due_date');
        $table->integer('closed_by')->nullable()->after('due_date');
        $table->date('close_date')->nullable()->after('due_date');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actions', function (Blueprint $table) {
            //
        });
    }
}
