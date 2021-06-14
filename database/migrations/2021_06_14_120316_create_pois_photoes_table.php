<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoisPhotoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pois_photoes', function (Blueprint $table) {
           $table->unsignedBigInteger('pois_id');
           $table->unsignedBigInteger('photoes_id');
           $table->integer('order');
           $table->foreign('pois_id')->references('id')->on('pois');
           $table->foreign('photoes_id')->references('id')->on('photoes');
           $table->unique(['pois_id', 'photoes_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
