<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Validator;

class ReviewController extends Controller
{
    //
    function addReview(Request $request){

        $validation = Validator::make($request->all(), [
            'vote'  => 'required|numeric',
            'comment'=> 'string',
            'place_id'=> 'required',
            'user_id'=> 'required',
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }
        try{
            $allData = $request->all();
            Review::create($allData);
            $data = ['message' => 'review inserted successfully','data'=>$allData];
            return Response()->json($data,201);
        }catch (Exception $exception){
            $response=[];
            $response['error']="an error has occured";
            return response()->json($response, 400);
        }
    }
}
