<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesPoisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes_pois', function (Blueprint $table) {
        $table->unsignedBigInteger('routes_id');
         $table->unsignedBigInteger('pois_id');
         $table->foreign('routes_id')->references('id')->on('routes');
         $table->foreign('pois_id')->references('id')->on('pois');
         $table->unique(['routes_id', 'pois_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pois_tags');
    }
}
