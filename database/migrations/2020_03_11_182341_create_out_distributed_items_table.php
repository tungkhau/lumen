<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateOutDistributedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('out_distributed_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->integer('distributed_quantity');
            $table->string('distributed_comment', 20)->nullable();
            $table->uuid('out_distribution_pk');
            $table->uuid('accessory_pk');

            $table->foreign('out_distribution_pk')->references('pk')->on('out_distributions');
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
        Schema::dropIfExists('out_distributed_items');
    }
}
