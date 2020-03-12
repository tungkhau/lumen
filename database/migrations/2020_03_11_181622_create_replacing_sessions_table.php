<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReplacingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replacing_sessions', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->dateTime('executed_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->uuid('start_shelf_pk');
            $table->uuid('end_shelf_pk');
            $table->uuid('user_pk');

            $table->foreign('user_pk')->references('pk')->on('users');
            $table->foreign('start_shelf_pk')->references('pk')->on('shelves');
            $table->foreign('end_shelf_pk')->references('pk')->on('shelves');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replacing_sessions');
    }
}
