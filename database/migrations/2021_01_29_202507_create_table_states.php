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
            $table->string('name');
            //$table->timestamps();
        });
        $states = json_decode(file_get_contents(storage_path('wilayas.json')), true)[0];
       // $states=$states[0];
        foreach ($states as $key=>$value){
            //echo $value["wilaya_id"];
           // echo $value["name"];
            // print_r($value);
            DB::table('states')->insert(['id'=>$value["wilaya_id"],'name'=>$value["name"]]);

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
