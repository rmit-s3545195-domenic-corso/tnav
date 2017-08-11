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
        Schema::create('RestroomPhotos', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('name');
	    $table->string('addedBy');
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
        Schema::dropIfExists('RestroomPhotos');
    }
}
