<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoriesConceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessories_conceptions', function (Blueprint $table) {
            $table->uuid('accessory_pk');
            $table->uuid('conception_pk');
            $table->foreign('accessory_pk')->references('pk')->on('accessories');
            $table->foreign('conception_pk')->references('pk')->on('conceptions');

            $table->unique(['accessory_pk', 'conception_pk']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accessories_conceptions');
    }
}
