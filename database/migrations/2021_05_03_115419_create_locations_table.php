<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('name_dat')->default('');
            $table->string('name_rod')->default('');
            $table->string('name_pred')->default('');
            $table->string('flag')->default('');
            $table->integer('parent');
            $table->integer('type');
            $table->integer('scale')->default(7);
            $table->float('lat')->default(0);
            $table->float('lng')->default(0);
            $table->integer('count')->default(0);
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
        Schema::dropIfExists('locations');
    }
}
