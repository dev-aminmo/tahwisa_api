<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\PlacePicture;
class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'latitude',
        'longitude',
        'user_id'
    ];

    public function pictures()
    {
        return $this->hasMany(PlacePicture::class);
    }
    public function reviews()
    {
        return $this->hasMany(PlacePicture::class);
    }
    public $timestamps = false;
    /*
     * this method will insert a place in places table and pictures in pictures table in the same time
     * if an error occured nothing will be inserted
     * */
    public function add($params){
        DB::transaction(function () use ($params){
          $id=  DB::table('places')->insertGetId([
                'title'=>$params["title"],
                'description'=>$params["description"],
                'latitude'=>$params["latitude"],
                'longitude'=>$params["longitude"],
                'user_id'=>$params["user_id"],
            ]);
            $pictures=$params['pictures'];
            foreach ($pictures as $k=> $picture){
                $arg=[];
                $arg['path']=$picture;
                DB::table('places_pictures')->insert([
                'path'=>$arg['path'], 'place_id'=>$id
                ]);

            }

        });
    }

}