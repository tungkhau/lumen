<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImportedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imported_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->integer('imported_quantity');
            $table->string('imported_comment', 20)->nullable();
            $table->string('contract_num', 20)->nullable();
            $table->uuid('import_pk');
            $table->uuid('ordered_item_pk');
            $table->uuid('classified_item_pk')->nullable();

            $table->foreign('import_pk')->references('pk')->on('imports');
            $table->foreign('ordered_item_pk')->references('pk')->on('ordered_items');
            $table->foreign('classified_item_pk')->references('pk')->on('classified_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imported_items');
    }
}
