<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceStatus extends Model
{
    use HasFactory;

    protected $table = "place_statuses";
    public $timestamps = false;
}
