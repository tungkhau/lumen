<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateClassifiedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classified_items', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->enum('quality_state', ['passed', 'failed', 'pending']);
            $table->uuid('sendbacking_session_pk');

            $table->foreign('sendbacking_session_pk')->references('pk')->on('sendbacking_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classified_items');
    }
}
