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
            $table->string('name');
            $table->bigInteger('state_id')->unsigned();
            $table->foreign("state_id")->references('id')->on("states");
            // $table->timestamps();
        });
        $states = json_decode(file_get_contents(storage_path('wilayas.json')), true)[0];
        foreach ($states as $key=>$value){
            foreach ($value["communes"] as $k=>$v) {
                echo $v["commune_id"];
                echo $v["name"];
                DB::table('municipales')->insert(['id'=>$v["commune_id"],'name'=>$v["name"],'state_id'=>$value["wilaya_id"]]);
            }
        }
    }

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
