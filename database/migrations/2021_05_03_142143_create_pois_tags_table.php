<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoisTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pois_to_tags', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('poi_id');
         $table->unsignedBigInteger('tag_id');
         $table->foreign('poi_id')->references('id')->on('pois');
         $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pois_to_tags');
    }
}
