<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateAdjustingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjusting_sessions', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->dateTime('executed_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->uuid('user_pk');
            $table->uuid('verifying_session_pk')->nullable();

            $table->foreign('user_pk')->references('pk')->on('users');
            $table->foreign('verifying_session_pk')->references('pk')->on('verifying_sessions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adjusting_sessions');
    }
}
