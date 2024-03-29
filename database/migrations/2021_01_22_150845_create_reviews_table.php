<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->decimal('vote', 1, 1);
            $table->string("comment")->nullable();
            $table->bigInteger("place_id")->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign("user_id")->references('id')->on("users");
            $table->foreign("place_id")->references('id')->on("places")->onDelete('cascade');;

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
        Schema::dropIfExists('reviews');
    }
}
