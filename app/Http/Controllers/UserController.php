<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    //
    public function registration(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }
        $allData = $request->all();
        $allData['password'] = bcrypt($allData['password']);
        $newUser = User::create($allData);
        $tokenStr = $newUser->createToken('api-application')->accessToken;
        $resArr["token"] = $tokenStr;
        $resArr["status code"] = 200;
        return response()->json($resArr, 200);
    }

    public function login(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            $user = Auth::user();
            $resArr = [];
            $resArr['token'] = $user->createToken('api-application')->accessToken;
            $resArr['name'] = $user->username;
            return response()->json($resArr, 200);
        } else {
            return response()->json(['error' => 'Unauthorized access','status code'=>401], 401);
        }
    }
    public function details()
    {
        $deatails=auth()->user();
        if($deatails['profile_picture']==""){
            $deatails['profile_picture']="place_holder.jpg" ;
        }
        return response()->json(['user'=> $deatails],200);
    }
    public function updateProfilePicture(Request $request){
     try{
         $id= auth()->user()['id'];

         // echo $id;die;
         $result=$request->file('file')->store('public/users/profilePictures/'.$id);
         $oldPath=auth()->user()['profile_picture'];
         User::where('id',$id)->update(['profile_picture'=>$result]);
         $data = ['message' => 'profile picture updated successfully','data'=>$result,'response code'=>201];
        //$url = Storage::url($result);
        Storage::delete($oldPath);
       // $data['url']=$url;
         return response()->json($data,201);
     }catch (Exception $e){
         $resArr["status code"] = 200;
         return response()->json($resArr, 200);

     }

    }


}
