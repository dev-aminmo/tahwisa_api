<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class TagController extends Controller
{
    //

    public function tags(Request $request){
        $query = $request->get( 'query');
        if($query){
        $data=    Tag::where("name","like","%".$query."%")->get();
        }else{
            $data= Tag::select("name")->get();

        }
        return response()->json($data,200);
    }
}
