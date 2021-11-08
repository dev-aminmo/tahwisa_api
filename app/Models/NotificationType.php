<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    use HasFactory;

    protected $table = "notification_types";
    protected $fillable = ['name'];
    public $timestamps = false;
}
