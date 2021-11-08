<?php

namespace App\Http\Controllers;

use App\Helpers\MyResponse;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\NotificationItem;
use App\Models\NotificationRefuseMessageItem;
use App\Models\RefusePlaceMessage;
use Illuminate\Http\Request;
use Validator;
use function Symfony\Component\Translation\t;

class NotificationController extends Controller
{
    use MyResponse;

    function index(Request $request)
    {
        $userId = auth()->user()->id;
        $userId = 1;
        $data = NotificationItem::where('user_id', $userId)->orderBy('created_at', 'DESC')
            ->whereDate('created_at', '>', \Carbon\Carbon::now()->subMonth())
            ->with('notification')->limit(50)->get();
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

    function getRefusePlaceMessages(Notification $notification)
    {
        try {
            $messagesIds = NotificationRefuseMessageItem::where('notification_id', $notification->id)->pluck('message_id');
            $data = RefusePlaceMessage::whereIn('id', $messagesIds)->orderBy('id', 'DESC')->get();
            return $this->returnDataResponse($data);
        } catch (\Exception $e) {
            return $this->returnErrorResponse();
        }

    }
}
