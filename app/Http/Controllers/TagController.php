<?php

namespace App\Http\Controllers;

use App\Helpers\MyResponse;
use App\Models\Tag;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class TagController extends Controller
{
    use MyResponse;
    //

    public function tags(Request $request){
        $query = $request->get( 'query');
        if($query){
        $data=    Tag::where("name","like","%".$query."%")->select("name")->get();
        }else{
            $data= Tag::select("name")->get();

        }
        return response()->json($data,200);
    }
    public function top(Request $request){
       $data= Tag::query()->take(10)->get();
        return $this->returnDataResponse($data);

    }
}
