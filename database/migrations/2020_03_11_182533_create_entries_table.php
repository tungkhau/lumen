<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->uuid('received_item_pk');
            $table->enum('kind', ['imported', 'restored', 'in_transferred']);
            $table->enum('entry_kind', ['storing', 'in', 'out', 'issuing', 'adjusting', 'discarding', 'returning']);
            $table->integer('quantity')->nullable();
            $table->uuid('session_pk');
            $table->uuid('case_pk');
            $table->uuid('accessory_pk');

            $table->foreign('accessory_pk')->references('pk')->on('accessories');
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
        Schema::dropIfExists('entries');
    }
}
