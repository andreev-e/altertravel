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
            $table->string('old_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('name');
            $table->string('url');
            $table->double('lat')->default('0');
            $table->double('lng')->default('0');
            $table->text('description')->nullable();
            $table->text('prim')->nullable();
            $table->text('route')->nullable();
            $table->text('route_o')->nullable();
            $table->string('video')->nullable();
            $table->integer('status')->default(1);
            $table->string('copyright')->nullable();
            $table->text('links')->nullable();;
            $table->unsignedBigInteger('views')->default('0');
            $table->string('dominatecolor')->default('#ffffff');
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
