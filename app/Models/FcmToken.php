<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class FcmToken extends Model
{
    use HasFactory;

    protected $table = "fcm_tokens";


    protected $fillable = [

        'token',
        'user_id',
    ];

    static function send($tokens, $notification)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);
        $notificationBuilder = new PayloadNotificationBuilder($notification->title);
        $notificationBuilder->setBody($notification->body)
            ->setSound('default');
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "status" => "done",
            'id' => $notification->id,
            'type' => $notification->type,
            'place_id' => $notification->place_id,
            'description' => $notification->description,
        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        /*   forEach($downstreamResponse->tokensToDelete() as  $token){
               DB::transaction(function () use ($token) {
                   FcmToken::where('token',$token)->delete();
                   return 'success';
               });
           }*/
        return $downstreamResponse;

    }
}
