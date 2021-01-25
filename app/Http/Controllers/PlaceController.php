<?php

namespace App\Http\Controllers;

use Exception;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\PlacePicture;
use Validator;

class PlaceController extends Controller
{
   public function addPlace(Request $request){
       $validation = Validator::make($request->all(), [
           'title'  => 'required',
           'latitude'=> 'required|numeric',
           'longitude'=> 'required|numeric',
           'user_id'=> 'required',
           'pictures'=>'required'
       ]);
       if ($validation->fails()) {
           return response()->json($validation->errors(), 202);
       }
       $allData = $request->all();
       try{
           $place= new Place();
           $place->add($allData);
           $data = ['message' => 'place inserted successfully','data'=>$allData];
           return Response()->json($data,201);
       }catch (Exception $exception){
           $response=[];
           $response['error']="an error has occured";
           return response()->json($response, 400);
       }



   }
}
