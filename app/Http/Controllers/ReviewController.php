<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Validator;

class ReviewController extends Controller
{
    //
    function addReview(Request $request){

        $validation = Validator::make($request->all(), [
            'vote'  => 'required|numeric',
            'comment'=> 'string',
            'place_id'=> 'required',
           // 'user_id'=> 'required',
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }

        try{
            $id= auth()->user()['id'];

            $allData = $request->all();
            $allData['user_id']=$id;
            Review::create($allData);
            $data = ['message' => 'review inserted successfully'];
            return Response()->json($data,201);
        }catch ( \Exception  $exception){
           // $response=[];
            $response['error']="an error has occured";
            return response()->json($response, 400);
        }
    }
    function get(Request $request){
        $id = Route::current()->parameter('id');
        try{
           $reviwes= Review::where('place_id',$id)->paginate(2);
            if(count($reviwes)==0){
                return response()->json(["error"=>401,"message"=>"place doesn't have reviews"],401);
            }
            return response()->json($reviwes,200);
        }catch (Exception $exception){
            return response()->json(["error"=>401,"message"=>"error occured"],401);
        }
    }
}
