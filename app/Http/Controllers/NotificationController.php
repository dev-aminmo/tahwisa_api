<?php

namespace App\Http\Controllers;

use App\Helpers\MyResponse;
use App\Http\Resources\NotificationResource;
use App\Models\NotificationItem;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use MyResponse;

    function index(Request $request)
    {
        $userId = auth()->user()->id;
        $data = NotificationItem::where('user_id', $userId)->with('notification')->get();
        $data = NotificationResource::collection($data);
        return $this->returnDataResponse($data);
    }
}
