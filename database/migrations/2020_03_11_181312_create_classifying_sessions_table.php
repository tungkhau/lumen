<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClassifyingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifying_sessions', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->dateTime('executed_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->uuid('user_pk');
            $table->uuid('classified_item_pk');

            $table->foreign('user_pk')->references('pk')->on('users');
            $table->foreign('classified_item_pk')->references('pk')->on('classified_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classifying_sessions');
    }
}
