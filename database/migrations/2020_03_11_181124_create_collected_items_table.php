<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateCollectedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collected_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->integer('collected_quantity');
            $table->uuid('collecting_pk');
            $table->uuid('in_distributed_item_pk');

            $table->foreign('collecting_pk')->references('pk')->on('collectings');
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
        Schema::dropIfExists('collected_items');
    }
}
