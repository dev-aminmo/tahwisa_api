<?php

namespace App\Http\Controllers;

use App\Models\WishListItem;
use Illuminate\Http\Request;
use Validator;

class WishController extends Controller
{
    //
    public function add(Request $request){
        $validation = Validator::make($request->all(), [
            'place_id'=> 'required',
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }
        try{
            $id= auth()->user()['id'];
            $allData=$request->all();
            $allData['user_id']=$id;
            WishListItem::create($allData);

            $data = ['message' => 'place added to wishlist successfully'];
            return Response()->json($data,201);

        }catch (\Exception $e){
            // $response=[];
            echo $e;
            $response['error']="an error has occured";
            return response()->json($response, 400);

        }

    }
}
