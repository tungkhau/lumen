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
            $table->foreign('received_group_pk')->references('pk')->on('received_groups');
            $table->foreign('arranging_session_pk')->references('pk')->on('arranging_sessions');
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
