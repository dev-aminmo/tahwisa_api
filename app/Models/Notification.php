<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";
    protected $fillable = ['title', 'body', 'description', 'type', 'place_id'];
    protected $hidden = ['laravel_through_key'];

    public $timestamps = false;

    public function getTypeAttribute($value)
    {
        return NotificationType::find($value)->name;
    }
}
