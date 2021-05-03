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
        Schema::create('pois_to_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poi_id');
           $table->unsignedBigInteger('location_id');
           $table->foreign('poi_id')->references('id')->on('pois');
           $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pois_to_locations');
    }
}
