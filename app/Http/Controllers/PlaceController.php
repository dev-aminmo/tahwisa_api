<?php

namespace App\Http\Controllers;

use Exception;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\PlacePicture;
use Validator;
use Illuminate\Support\Facades\Route;


class PlaceController extends Controller
{
   public function addPlace(Request $request){
       $validation = Validator::make($request->all(), [
           'title'  => 'required|string',
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
   public function updatePlaceInfo(Request $request){
       $id = Route::current()->parameter('id');

       $validation = Validator::make($request->all(), [
           'title'  => 'string',
           'description'=>'string',
           'latitude'=> 'numeric',
           'longitude'=> 'numeric',
           //'pictures'=>'array'
       ]);
       if ($validation->fails()) {
           return response()->json($validation->errors(), 202);
       }
       $allData = $request->all();
       try{
           Place::where('id',$id)->update($allData);
           $data = ['message' => 'place updated successfully','data'=>$allData,'response code'=>201];
           return Response()->json($data,201);
       }catch (Exception $exception){
           $response=[];
           echo $exception;
           $response['error']="an error has occured";
           return response()->json($response, 400);
       }



   }
  /* public function updatePlacePictures(Request $request){
       $id = Route::current()->parameter('id');

       $validation = Validator::make($request->all(), [
              'pictures'=>'array'
       ]);
       if ($validation->fails()) {
           return response()->json($validation->errors(), 202);
       }
       $pictures = $request->pictures;
       try{

             foreach ($pictures as $k=> $picture){
               $arg=[];
               $arg['path']=$picture;
               $arg['place_id']=$id;
               PlacePicture::where('place_id',$id)->update($arg);
           }
           $data = ['message' => 'place pictures updated successfully','data'=>$pictures,'response code'=>201];
           return Response()->json($data,201);
       }catch (Exception $exception){
           $response=[];
           echo $exception;
           $response['error']="an error has occured";
           return response()->json($response, 400);
       }



   }*/
}
