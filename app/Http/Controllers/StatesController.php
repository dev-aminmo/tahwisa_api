<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Municipal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class StatesController extends Controller
{
    //
    public function all(Request $request){
        $states =State::all();
        return response()->json([
            "message"=>"success",
            "data"=>$states,
    ],200);
    }
    public function municipales(Request $request){
     //   dd($request->id);
      //  echo($request->id);die;
        $id = Route::current()->parameter('id');
        $states =Municipal::where('state_id',$id)->get();
        return response()->json([
            "message"=>"success",
            "data"=>$states,
    ],200);
    }

}
