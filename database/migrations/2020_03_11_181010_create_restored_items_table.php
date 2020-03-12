<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRestoredItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restored_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->integer('restored_quantity');
            $table->uuid('restoration_pk');
            $table->uuid('accessory_pk');

            $table->foreign('restoration_pk')->references('pk')->on('restorations');
            $table->foreign('accessory_pk')->references('pk')->on('accessories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restored_items');
    }
}
