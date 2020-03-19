<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrderedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->integer('ordered_quantity');
            $table->string('ordered_comment', 20)->nullable();
            $table->uuid('order_pk');
            $table->uuid('accessory_pk');

            $table->foreign('order_pk')->references('pk')->on('orders');
            $table->foreign('accessory_pk')->references('pk')->on('accessories');

            $table->unique(['order_pk', 'accessory_pk']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordered_items');
    }
}
