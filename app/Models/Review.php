<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'vote',
        'comment',
        'place_id',
        'user_id'
    ];

    public function place(){
        return $this->belongsTo(Place::class);
    }
}
