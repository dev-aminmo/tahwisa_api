<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
           // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_picture',255)->nullable();
            $table->string('provider_name')->nullable();
            $table->string('provider_id')->nullable();
            $table->bigInteger("role")->unsigned()->default(1);
            $table->foreign("role")->references('id')->on("roles");
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
