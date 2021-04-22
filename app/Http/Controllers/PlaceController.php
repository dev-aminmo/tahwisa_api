<?php

namespace App\Http\Controllers;

use Exception;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Review;
use App\Models\PlacePicture;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Route;


class PlaceController extends Controller
{
   public function addPlace(Request $request){


       $validation = Validator::make(
          $request->all(), [
              'data'=>'required',
           'file'=>'required',
           'file.*' => 'required|mimes:jpg,jpeg,png,bmp|max:20000',
          ],[
               'file.*.required' => 'Please upload an image',
               'file.*.mimes' => 'Only jpeg,png and bmp images are allowed',
               'file.*.max' => 'Sorry! Maximum allowed size for an image is 20MB',
           ]
       );
       if($validation->fails()) {
         return response()->json($validation->errors(), 202);
       }
       $jsonData=$request->get("data");
       if(!is_array($jsonData)) $jsonData= json_decode($request->get("data"),true);
       $validation = Validator::make($jsonData, [
           'title'  => 'required|string',
           'description'=>'string',
           'latitude'=> 'required|numeric',
           'longitude'=> 'required|numeric',
           'municipal_id'=>'required|numeric',
       ]);

       if ($validation->fails()) {
           return response()->json($validation->errors(), 202);
       }
       $id= auth()->user()['id'];
       try{
           Place::add($jsonData,$request->file('file'),$id);
           $data = ['message' => 'place inserted successfully','code'=>201];
           return Response()->json($data,201);
       }catch (Exception $exception){
           $response=[];
           $response['error']="an error has occured";
           return response()->json($exception, 400);
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
   public function all(Request $request){
         $places =Place::has('pictures')->with(['pictures'=> function ($query){
              $query->select(//
                  'path',
                  'place_id'
              )->limit(1);;
          }])->withAvg('reviews','vote')->paginate(10);
       return response()->json($places,200);
   }
   public function  get(Request $request){
       $id = Route::current()->parameter('id');
       try{
           $place =Place::where('id',$id)->with(['pictures'])->withAvg('reviews','vote')->withCount('reviews')->with(['user'=> function ($query) {
               $query->select(
                   'username',
               'profile_picture');
           }])->get();
           if(count($place)==0){
               return response()->json(["error"=>401,"message"=>"place doesn't exist"],401);
           }
           return response()->json($place,200);
       }catch (Exception $exception){
           return response()->json(["error"=>401,"message"=>"place doesn't exist"],401);
       }

   }
}
