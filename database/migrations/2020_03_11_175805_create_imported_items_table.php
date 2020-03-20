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
            $table->string('comment', 20)->nullable();
            $table->uuid('import_pk');
            $table->uuid('ordered_item_pk');
            $table->uuid('classified_item_pk')->nullable()->default(null);

            $table->foreign('import_pk')->references('pk')->on('imports');
            $table->foreign('ordered_item_pk')->references('pk')->on('ordered_items');
            $table->foreign('classified_item_pk')->references('pk')->on('classified_items');

            $table->unique(['import_pk', 'ordered_item_pk']);
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
