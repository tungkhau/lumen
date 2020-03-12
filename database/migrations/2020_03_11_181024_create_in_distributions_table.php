<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_distributions', function (Blueprint $table) {
            $table->uuid('pk')->primary()->default(DB::raw('UUID()'));
            $table->string('id', 999)->unique(); //TODO Declare exact length;
            $table->string('distribution_comment', 20)->nullable();
            $table->dateTime('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('is_opened')->default(true);
            $table->uuid('site_pk');

            $table->foreign('site_pk')->references('pk')->on('sites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('in_distributions');
    }
}
