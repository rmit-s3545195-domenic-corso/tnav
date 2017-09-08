<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameRestroomPhotosForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restroom_photos', function (Blueprint $table) {
            $table->renameColumn('restroomID', 'restroom_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restroom_photos', function (Blueprint $table) {
            $table->renameColumn('restroom_id', 'restroomID');
        });
    }
}
