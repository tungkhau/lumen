<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReceivedGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_groups', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->enum('kind', ['imported', 'restored', 'collected']);
            $table->integer('grouped_quantity');
            $table->uuid('received_item_pk');
            $table->uuid('case_pk');
            $table->uuid('receiving_session_pk')->nullable();
            $table->uuid('counting_session_pk')->nullable()->unique()->default(null);
            $table->uuid('checking_session_pk')->nullable()->unique()->default(null);
            $table->uuid('storing_session_pk')->nullable()->default(null);

            $table->foreign('case_pk')->references('pk')->on('cases');
            $table->foreign('receiving_session_pk')->references('pk')->on('receiving_sessions');
            $table->foreign('counting_session_pk')->references('pk')->on('counting_sessions');
            $table->foreign('checking_session_pk')->references('pk')->on('checking_sessions');
            $table->foreign('storing_session_pk')->references('pk')->on('storing_sessions');

            $table->unique(['received_item_pk', 'kind']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_groups');
    }
}
