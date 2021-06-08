<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

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

        try{
            $allData = $request->all();
            $allData['password'] = bcrypt($allData['password']);
            $newUser = User::create($allData);
            $tokenStr = $newUser->createToken('api-application')->accessToken;
            $resArr["token"] = $tokenStr;
            $resArr["status code"] = 201;
            return response()->json($resArr, 201);
        }catch (\Exception $e){

            return response()->json(["error"=>$e->getMessage()], 400);

        }

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
         $response = cloudinary()->upload($request->file('file')->getRealPath(),[
             'folder'=> 'tahwisa/users/'.$id.'/',
             'public_id'=>'profile_picture'.$id,
             'overwrite'=>true,
             'format'=>"webp"
         ])->getSecurePath();
         $oldPath=auth()->user()['profile_picture'];
         User::where('id',$id)->update(['profile_picture'=>$response]);
         $data = ['message' => 'profile picture updated successfully','data'=>$response,'response code'=>201];
        Storage::delete($oldPath);
         return response()->json($data,201);
     }catch (Exception $e){
         $resArr["status code"] = 200;
         return response()->json($resArr, 200);

     }

    }
    /**
     * Social Login
     */
    public function socialLogin(Request $request)
    {
        $provider = "google"; // or $request->input('provider_name') for multiple providers
        $token = $request->input('access_token');
        // get the provider's user. (In the provider server)
        $providerUser = Socialite::driver($provider)->userFromToken($token);
        $u["id"]= $providerUser->id;
        $u["username"]= $providerUser->name;
        $u["profile_picture"]= $providerUser->avatar;
        $u["email"]= $providerUser->email;


        // check if access token exists etc..
        // search for a user in our server with the specified provider id and provider name
        $user = User::where('provider_name', $provider)->where('provider_id', $providerUser->id)->first();
        // if there is no record with these data, create a new user
        if($user == null) {
            $user = User::where('email', $u["email"])->first();
            if(!empty($user)){
                $user->provider_name=$provider;
                $user->provider_id= $providerUser->id;
               $user->save();
            }else{
                $user = User::create([
                    "username"=>$u["username"],
                    "profile_picture"=>$u["profile_picture"],
                    "email"=> $u["email"],
                    "password"=> Hash::make(str_random(8)),
                    'provider_name' => $provider,
                    'provider_id' => $providerUser->id,
                ]);
            }}
        // create a token for the user, so they can login
        $token = $user->createToken(env('APP_NAME'))->accessToken;
        // return the token for usage
        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
