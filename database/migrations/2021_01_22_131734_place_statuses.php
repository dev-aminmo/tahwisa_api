<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlaceStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        DB::table('place_statuses')->insert([
            'name' => "initial",
        ]);
        DB::table('place_statuses')->insert([
            'name' => "approved",
        ]);
        DB::table('place_statuses')->insert([
            'name' => "refused",
        ]);
        DB::table('place_statuses')->insert([
            'name' => "deleted",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
