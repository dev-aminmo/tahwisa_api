<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Tag extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable =['id', 'name'];
    public $timestamps=false;

    protected $hidden = ['laravel_through_key'];
    /*public function places()
    {
        return $this->belongsToMany(Place::class, 'places_tags', 'place_id', 'tag_id');
    }*/
    /*public function places()
    {
        return $this->hasMany(PlaceTag::class,'tag_id');
    }*/
    public function places()
    {
        return $this->hasManyThrough(Place::class, PlaceTag::class,
            'tag_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
    public function getModelAttribute()
    {

        return  $this->model="tag";
    }

}
