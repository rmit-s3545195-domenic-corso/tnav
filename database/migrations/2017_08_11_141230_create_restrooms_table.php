<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restrooms', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('name');
	    $table->string('description');
	    $table->string('lat');
	    $table->string('lng');
	    $table->string('floor');
	    $table->string('addedBy');
	    $table->integer('reports');
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
        Schema::dropIfExists('restrooms');
    }
}
