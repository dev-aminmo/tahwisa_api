<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



class CreateTableStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_fr');

        });
        $states = json_decode(file_get_contents(storage_path('algeria-cities.json')), true)["wilayas"];
        foreach ($states as $key=>$value){
            DB::table('states')->insert(['id'=>$value["id"],'name_ar'=>$value["name_ar"],
                'name_fr'=>$value["name"]
            ]);

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
