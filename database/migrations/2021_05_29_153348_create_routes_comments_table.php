<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes_comments', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('routes_id');
          $table->foreign('routes_id')->references('id')->on('pois');
          $table->unsignedBigInteger('users_id')->nullable();
          $table->foreign('users_id')->references('id')->on('users');
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
        Schema::dropIfExists('routes_comments');
    }
}
