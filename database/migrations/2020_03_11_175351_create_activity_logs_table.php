<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(Str::uuid());
            $table->string('id', 25); //Maximum length for Order ID
            $table->enum('type', ['create', 'update', 'delete', 'deactivate', 'reactivate', 'link', 'unlink', 'photo_update']);
            $table->enum('object', ['accessory', 'customer', 'supplier', 'conception']);
            $table->uuid('user_pk');

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
        Schema::dropIfExists('activity_logs');
    }
}
