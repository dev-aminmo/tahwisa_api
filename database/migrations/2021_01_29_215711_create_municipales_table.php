<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class CreateMunicipalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipales', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_fr');
            $table->bigInteger('state_id')->unsigned();
            $table->foreign("state_id")->references('id')->on("states");
        });
        $states = json_decode(file_get_contents(storage_path('algeria-cities.json')), true)["wilayas"];
        foreach ($states as $key=>$value){
            foreach ($value["dairas"] as &$daira) {
                if(key_exists("communes",$daira))   foreach ($daira["communes"] as &$v) {
                    DB::table('municipales')->insert([
                        'id'=>$v["code"],'name_ar'=>$v["name_ar"]
                        ,'name_fr'=>$v["name"],
                        'state_id'=>$value["code"]]);
                }



            }}}




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipales');
    }
}


