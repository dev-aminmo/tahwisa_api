<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places_pictures', function (Blueprint $table) {
            $table->id();
            $table->string("path",2083);
            $table->bigInteger("place_id")->unsigned();
            $table->foreign("place_id")->references('id')->on("places")->onDelete('cascade');;
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places_pictures');
    }
}
