<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateDemandedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demanded_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->integer('demanded_quantity');
            $table->string('demanded_comment', 20)->nullable();
            $table->uuid('demand_pk');
            $table->uuid('accessory_pk');

            $table->foreign('demand_pk')->references('pk')->on('demands');
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
        Schema::dropIfExists('demanded_items');
    }
}
