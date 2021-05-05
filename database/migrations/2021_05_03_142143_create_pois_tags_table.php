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
        Schema::create('pois_tags', function (Blueprint $table) {
          $table->unsignedBigInteger('pois_id');
         $table->unsignedBigInteger('tags_id');
         $table->foreign('pois_id')->references('id')->on('pois');
         $table->foreign('tags_id')->references('id')->on('tags');
         $table->unique(['pois_id', 'tags_id']);
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
