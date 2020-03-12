<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateRestorationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restorations', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->string('id', 999)->unique(); //TODO Declare exact length;
            $table->string('restoration_comment', 20)->nullable();
            $table->dateTime('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->uuid('user_pk');
            $table->uuid('receiving_session_pk')->nullable();

            $table->foreign('receiving_session_pk')->references('pk')->on('receiving_sessions');
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
        Schema::dropIfExists('restorations');
    }
}
