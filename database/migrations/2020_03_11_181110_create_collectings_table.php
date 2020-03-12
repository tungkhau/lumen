<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCollectingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectings', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->dateTime('sent_date');
            $table->uuid('in_distribution_pk');
            $table->uuid('receiving_session_pk')->nullable();

            $table->foreign('in_distribution_pk')->references('pk')->on('in_distributions');
            $table->foreign('receiving_session_pk')->references('pk')->on('receiving_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectings');
    }
}
