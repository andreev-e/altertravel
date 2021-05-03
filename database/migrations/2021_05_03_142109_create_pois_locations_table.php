<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoisLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pois_locations', function (Blueprint $table) {
            $table->unsignedBigInteger('pois_id');
           $table->unsignedBigInteger('locations_id');
           $table->foreign('pois_id')->references('id')->on('pois');
           $table->foreign('locations_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pois_locations');
    }
}
