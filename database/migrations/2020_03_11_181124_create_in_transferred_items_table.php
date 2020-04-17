<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInTransferredItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_transferred_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->integer('in_transferred_quantity');
            $table->uuid('in_transfer_pk');
            $table->uuid('in_distributed_item_pk');

            $table->foreign('in_transfer_pk')->references('pk')->on('in_transfers');
            $table->foreign('in_distributed_item_pk')->references('pk')->on('in_distributed_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('in_transferred_items');
    }
}
