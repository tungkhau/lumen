<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateIssuedGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issued_groups', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->enum('kind', ['consumed', 'transferred']);
            $table->integer('grouped_quantity');
            $table->uuid('received_item_pk');
            $table->uuid('issuing_session_pk');
            $table->uuid('issued_item_pk');
            $table->uuid('case_pk');

            $table->foreign('issuing_session_pk')->references('pk')->on('issuing_sessions');
            $table->foreign('issued_item_pk')->references('pk')->on('issued_items');
            $table->foreign('case_pk')->references('pk')->on('cases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issued_groups');
    }
}
