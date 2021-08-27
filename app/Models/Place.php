<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\PlacePicture;
use Laravel\Scout\Searchable;

class Place extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use HasFactory;
    use Searchable;
    public $timestamps = false;
    protected $hidden = ['laravel_through_key'];


    protected $fillable = [
        'title',
        'description',
        'latitude',
        'longitude',
        'user_id',
        'municipal_id'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function municipal(){
        return $this->belongsTo(Municipal::class,'municipal_id');
    }
    public function pictures()
    {
        return $this->hasMany(PlacePicture::class,'place_id');
    }
   /* public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }*/
    public function getMunicipalIdAttribute($value)
    {
        return Municipal::where("id",$value)->select("id","name_fr","state_id")->with(["state"=>function($query){
            $query->select("id","name_fr");
        }])->first();
    }
        public function getModelAttribute()
    {

        return  $this->model="place";
    }

    public function reviews()
    {
        return $this->hasMany(Review::class,'place_id');
    }

    public function wishes()
    {
        return $this->hasMany(WishListItem::class,'place_id');
    }


    /*public function tags()
    {
        return $this->hasMany(PlaceTag::class,'place_id');
    }*/
    public function tags()
    {
        return $this->hasManyThrough(Tag::class, PlaceTag::class,
                 'place_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }

    public function toSearchableArray() {
       // $a = $this->toArray();
        $tags = $this->tags()->get(['name'])->map( function ($tag) {
            return $tag['name'];
        });
        $a['tags'] = implode(' ', $tags->toArray());
       // return $a;
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
          'tags'=> $a['tags'],
           // 'path' => $this->ancestorsAndSelf->pluck('objectable.name')->reverse()->join('/'),
        ];
    }



    public static  function add($jsonData,$files,$id){
        $placeid=  DB::table('places')->insertGetId([
            'title'=>$jsonData["title"],
            'description'=>$jsonData["description"],
            'latitude'=>$jsonData["latitude"],
            'longitude'=>$jsonData["longitude"],
            'user_id'=>$id,
            'municipal_id'=>$jsonData["municipal_id"]
        ]);

        $pictures=[];
        foreach($files as $file)
        {
            $pictures[] = cloudinary()->upload($file->getRealPath(),[
                'folder'=> 'tahwisa/places/'.$placeid.'/',
                'format'=>"webp",
            ])->getSecurePath();
        }
        foreach ($pictures as $k=> $picture){
            $arg=[];
            $arg['path']=$picture;
            DB::table('places_pictures')->insert([
                'path'=>$arg['path'], 'place_id'=>$placeid
            ]);

        }
        /*DB::transaction(function () use ($params){
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

        });*/
    }

}
