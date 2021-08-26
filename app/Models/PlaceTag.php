<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceTag extends Model
{
    use HasFactory;
    protected $table ="place_tag";


    public function tag(){
        return $this->belongsTo(Tag::class,"tag_id");
    }
    public function place(){
        return $this->belongsTo(Place::class,"place_id");
    }
}
