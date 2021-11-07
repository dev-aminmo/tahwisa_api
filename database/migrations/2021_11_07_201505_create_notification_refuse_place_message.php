<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationRefusePlaceMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_refuse_place_message', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('notification_id')->unsigned();
            $table->foreign("notification_id")->references('id')->on("notifications");
            $table->bigInteger('message_id')->unsigned();
            $table->foreign("message_id")->references('id')->on("refuse_place_messages");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_refuse_place_message');
    }
}
