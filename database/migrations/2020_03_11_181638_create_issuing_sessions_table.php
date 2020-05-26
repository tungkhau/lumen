<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIssuingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuing_sessions', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->string('id', 21)->unique();
            $table->enum('kind', ['consuming', 'transferring']);
            $table->dateTime('executed_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->uuid('container_pk'); // {consuming} demand >< {transferring} out_distribution
            $table->uuid('user_pk');
            $table->uuid('returning_session_pk')->nullable()->default(Null);
            $table->uuid('progressing_session_pk')->nullable()->default(Null);


            $table->foreign('user_pk')->references('pk')->on('users');
            $table->foreign('returning_session_pk')->references('pk')->on('returning_sessions');
            $table->foreign('progressing_session_pk')->references('pk')->on('progressing_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issuing_sessions');
    }
}
