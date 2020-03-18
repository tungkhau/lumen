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
            $table->enum('kind', ['imported', 'restored', 'collected']);
            $table->enum('entry_kind', ['storing', 'in', 'out', 'issuing', 'neg_adjusting', 'pos_adjusting', 'discarding', 'returning']);
            $table->integer('quantity');
            $table->uuid('session_pk');
            $table->boolean('is_pending')->default(false);
            $table->uuid('received_item_pk');
            $table->uuid('case_pk');

            $table->foreign('case_pk')->references('pk')->on('cases');

            $table->unique(['kind', 'received_item_pk']);
            $table->unique(['entry_kind', 'session_pk']);
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
