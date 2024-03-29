<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefusePlaceMessage extends Model
{
    use HasFactory;

    protected $table = 'refuse_place_messages';
    protected $fillable = ['name'];
    protected $hidden = ['laravel_through_key'];

    public $timestamps = false;
}
