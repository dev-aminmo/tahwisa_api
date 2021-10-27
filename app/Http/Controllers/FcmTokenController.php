<?php

namespace App\Http\Controllers;

use App\Helpers\MyResponse;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FcmTokenController extends Controller
{
    use MyResponse;

    function add(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'token' => 'required|string|unique:fcm_tokens',
        ]);
        if ($validation->fails())
            return response()->json($validation->errors(), 202);
        try {
            FcmToken::create([
                'token' => $request->token,
                'user_id' => Auth::user()->id
            ]);

            return $this->returnSuccessResponse('token inserted successfully');
        } catch (\Exception $e) {
            return $this->returnErrorResponse('an error has occurred', 500);

        }

    }
}
