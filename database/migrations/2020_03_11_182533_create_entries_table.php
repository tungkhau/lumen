<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->enum('kind', ['imported', 'restored', 'collected']);
            $table->enum('entry_kind', ['storing', 'in', 'out', 'issuing', 'neg_adjusting', 'pos_adjusting', 'discarding', 'returning']);
            $table->integer('quantity');
            $table->uuid('session_pk');
            $table->uuid('received_item_pk');
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
