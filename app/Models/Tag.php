<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'picture', 'top'];
    public $timestamps = false;

    protected $hidden = ['laravel_through_key'];

    public function places()
    {
        return $this->hasManyThrough(Place::class, PlaceTag::class,
            'tag_id',
            'id',
            'id',
            'id'
        );
    }
    public function getModelAttribute()
    {

        return  $this->model="tag";
    }
    public function tags(){
        return $this->hasMany(PlaceTag::class,"tag_id");
    }
}
