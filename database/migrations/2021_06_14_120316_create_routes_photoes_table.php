<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesPhotoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes_photoes', function (Blueprint $table) {
           $table->unsignedBigInteger('routes_id');
           $table->unsignedBigInteger('photoes_id');
           $table->integer('order');
           $table->foreign('routes_id')->references('id')->on('routes');
           $table->foreign('photoes_id')->references('id')->on('photoes');
           $table->unique(['routes_id', 'photoes_id']);
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
