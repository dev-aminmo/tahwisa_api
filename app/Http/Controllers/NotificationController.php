<?php

namespace App\Http\Controllers;

use App\Helpers\MyResponse;
use App\Http\Resources\NotificationResource;
use App\Models\NotificationItem;
use Illuminate\Http\Request;
use Validator;
use function Symfony\Component\Translation\t;

class NotificationController extends Controller
{
    use MyResponse;

    function index(Request $request)
    {
        //$userId = auth()->user()->id;
        $userId = 1;
        $data = NotificationItem::where('user_id', $userId)->orderBy('created_at', 'DESC')
            ->whereDate('created_at', '>', \Carbon\Carbon::now()->subMonth())
            ->with('notification')->get();
        $data = NotificationResource::collection($data);
        return $this->returnDataResponse($data);
    }

    function read(Request $request, $id)
    {
        try {
            $userId = 1;
            $notification = NotificationItem::where(['user_id' => $userId, 'notification_id' => $id])->firstOrFail();
            $notification->update(['read' => true]);
            return $this->returnSuccessResponse("notification updated successfully");
        } catch (\Exception $e) {
            return $this->returnErrorResponse();
        }
    }
}
