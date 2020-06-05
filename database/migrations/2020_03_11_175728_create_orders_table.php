<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->string('id', 30)->unique();
            $table->boolean('is_opened')->default(true);
            $table->dateTime('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->uuid('supplier_pk');
            $table->uuid('user_pk');

            $table->foreign('supplier_pk')->references('pk')->on('suppliers');
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
        Schema::dropIfExists('orders');
    }
}
