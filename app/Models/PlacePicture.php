<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Place;
class PlacePicture extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use HasFactory;

    protected $table="places_pictures";
    protected $fillable=[
        "path",
    "place_id",
        ];
    protected $hidden=['place_id'];
    public $timestamps = false;
    public function place(){
        return $this->belongsTo(Place::class);
    }
}
