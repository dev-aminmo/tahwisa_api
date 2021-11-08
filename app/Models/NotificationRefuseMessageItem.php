<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRefuseMessageItem extends Model
{
    use HasFactory;

    protected $table = "notification_refuse_place_message";
    protected $fillable = ['notification_id', 'message_id'];
    public $timestamps = false;
}
