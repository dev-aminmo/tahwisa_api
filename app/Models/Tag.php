<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable =[""];
    public $timestamps=false;

    public function places()
    {
        return $this->belongsToMany(Place::class, 'places_tags', 'place_id', 'tag_id');
    }
    public function getModelAttribute()
    {

        return  $this->model="tag";
    }

}
