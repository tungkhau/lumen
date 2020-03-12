<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateMovingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moving_sessions', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->dateTime('executed_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->uuid('start_case_pk');
            $table->uuid('end_case_pk');
            $table->uuid('user_pk');

            $table->foreign('user_pk')->references('pk')->on('users');
            $table->foreign('start_case_pk')->references('pk')->on('cases');
            $table->foreign('end_case_pk')->references('pk')->on('cases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moving_sessions');
    }
}
