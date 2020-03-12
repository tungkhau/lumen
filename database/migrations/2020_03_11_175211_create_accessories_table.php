<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->char('id', 16)->unique();
            $table->string('item', 20);
            $table->string('art', 20)->nullable();
            $table->string('color', 20)->nullable();
            $table->string('size', 10)->nullable();
            $table->string('name', 50);
            $table->string('comment', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('photo')->default('default');
            $table->uuid('type_pk');
            $table->uuid('unit_pk');
            $table->uuid('customer_pk');
            $table->uuid('supplier_pk');

            $table->foreign('type_pk')->references('pk')->on('types');
            $table->foreign('unit_pk')->references('pk')->on('units');
            $table->foreign('customer_pk')->references('pk')->on('customers');
            $table->foreign('supplier_pk')->references('pk')->on('suppliers');

            $table->unique(['item', 'customer_pk', 'supplier_pk']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accessories');
    }
}
