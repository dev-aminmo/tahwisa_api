<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places_tags', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('place_id')->unsigned();
            $table->bigInteger('tag_id')->unsigned();
            $table->foreign("place_id")->references('id')->on("places");
            $table->foreign("tag_id")->references('id')->on("tags");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places_tags');
    }
}
