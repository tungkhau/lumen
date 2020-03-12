<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDemandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demands', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->string('id', 999)->unique(); //TODO Declare exact length;
            $table->smallInteger('product_quantity');
            $table->boolean('is_opened')->default(true);
            $table->uuid('workplace_pk');
            $table->uuid('conception_pk');
            $table->uuid('user_pk');

            $table->foreign('workplace_pk')->references('pk')->on('workplaces');
            $table->foreign('conception_pk')->references('pk')->on('conceptions');
            $table->foreign('user_pk')->references('pk')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demands');
    }
}
