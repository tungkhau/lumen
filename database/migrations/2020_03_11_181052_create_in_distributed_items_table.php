<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInDistributedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_distributed_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->integer('distributed_quantity');
            $table->string('distributed_comment', 20)->nullable();
            $table->uuid('in_distribution_pk');
            $table->uuid('accessory_pk');

            $table->foreign('in_distribution_pk')->references('pk')->on('in_distributions');
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
        Schema::dropIfExists('in_distributed_items');
    }
}
