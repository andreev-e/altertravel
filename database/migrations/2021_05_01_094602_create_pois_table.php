<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pois', function (Blueprint $table) {
            $table->id();
            $table->integer('old_id')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('url');
            $table->double('lat')->default('0');
            $table->double('lng')->default('0');
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->string('prim')->nullable();
            $table->string('route')->nullable();
            $table->string('video')->nullable();
            $table->integer('status')->default(0);
            $table->string('photo')->default('no-photo.jpg');
            $table->string('photos')->nullable();
            $table->string('views')->default('0');
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
        Schema::dropIfExists('pois');
    }
}
