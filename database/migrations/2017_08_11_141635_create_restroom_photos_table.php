<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestroomPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restroom_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('addedBy')->nullable();
            $table->string('path');
            $table->integer('reports');
            $table->integer('restroomID');
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
        Schema::dropIfExists('restroom_photos');
    }
}
