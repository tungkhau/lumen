<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProgressingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progressing_sessions', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->enum('kind', ['confirming', 'delivering']);
            $table->dateTime('executed_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->uuid('user_pk');

            $table->foreign('user_pk')->references('pk')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progressing_sessions');
    }
}
