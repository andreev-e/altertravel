<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('old_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('url');
            $table->string('budget');
            $table->string('duration');
            $table->double('lat')->default('0');
            $table->double('lng')->default('0');
            $table->text('description')->nullable();
            $table->text('prim')->nullable();
            $table->string('video')->nullable();
            $table->integer('status')->default(1);
            $table->string('photo')->default('no-photo.jpg');
            $table->string('photos')->nullable();
            $table->text('links')->nullable();;
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
        Schema::dropIfExists('routes');
    }
}
