<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoisCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pois_comments', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('poi_id');
          $table->foreign('poi_id')->references('id')->on('pois');
          $table->unsignedBigInteger('user_id')->nullable();
          $table->foreign('user_id')->references('id')->on('users');
          $table->text('comment');
          $table->string('email')->default('');
          $table->unsignedBigInteger('parent')->default(0);
          $table->integer('status')->default(1);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pois_comments');
    }
}
