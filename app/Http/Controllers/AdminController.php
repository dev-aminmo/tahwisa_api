<?php

namespace App\Http\Controllers;

use App\Helpers\MyResponse;
use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\NotificationItem;
use App\Models\Place;
use App\Models\RefusePlaceMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;


class AdminController extends Controller
{
    use MyResponse;

    public function approvePlace(Place $place)
    {
        if ($place->status == 'approved' || $place->status == 'refused') {
            return $this->returnErrorResponse('post has been handled');
        }
        try {
            $e = Place::approve($place);
            $message = ($e) ? ['place approved successfully', $e] : 'place approved successfully';
            return $this->returnSuccessResponse($message);
        } catch (\Exception $e) {
            return $this->returnErrorResponse('An error has occurred');
        }
    }

    public function refusePlace(Request $request, Place $place)
    {
        $validation = Validator::make($request->all(), [
            'messages' => 'required',
            'messages.*' => 'exists:refuse_place_messages,id|distinct',
            'description' => 'string',
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }
        if (!Place::available($place)) {
            return $this->returnErrorResponse('post has been handled');
        }
        try {
            $e = Place::refuse($place, $request->description, $request->messages);
            $message = ($e) ? ['place refused successfully', $e] : 'place refused successfully';
            return $this->returnSuccessResponse($message);
        } catch (\Exception $e) {
            return $this->returnErrorResponse('An error has occurred');
        }
    }

    public function checkIfPlaceIsAvailable(Place $place)
    {
        $available = Place::available($place);
        return ($available) ? $this->returnSuccessResponse('post is available', 200) : $this->returnErrorResponse('post is not available');
    }

    public function getRefusePlaceMessages()
    {
        try {
            $data = RefusePlaceMessage::all();
            return $this->returnDataResponse($data);
        } catch (\Exception $e) {
            return $this->returnErrorResponse();
        }
    }
}
