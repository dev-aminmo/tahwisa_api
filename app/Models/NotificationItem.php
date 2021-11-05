<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationItem extends Model
{
    use HasFactory;

    protected $table = "user_notification";
    protected $fillable = ['user_id', 'notification_id', 'read'];

    public function notification()
    {
        return $this->belongsTo(Notification::class, "notification_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

}
