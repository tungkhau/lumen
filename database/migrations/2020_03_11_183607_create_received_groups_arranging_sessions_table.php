<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivedGroupsArrangingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_groups_arranging_sessions', function (Blueprint $table) {
            $table->uuid('received_group_pk');
            $table->uuid('arranging_session_pk');
            $table->foreign('received_group_pk')->references('pk')->on('received_groups');
            $table->foreign('arranging_session_pk')->references('pk')->on('arranging_sessions');

            $table->unique(['received_group_pk', 'arranging_session_pk'], 'unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_groups_arranging_sessions');
    }
}
