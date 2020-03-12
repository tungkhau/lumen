<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateIssuedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issued_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->enum('kind', ['consumed', 'transferred']);
            $table->integer('issued_quantity');
            $table->uuid('end_item_pk'); // {consumed} demanded_item >< {transferred} out_distributed_item
            $table->uuid('issuing_session_pk');

            $table->foreign('issuing_session_pk')->references('pk')->on('issuing_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issued_items');
    }
}
