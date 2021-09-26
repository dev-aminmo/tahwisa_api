<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\PlacePicture;

class Place extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use HasFactory;
    public $timestamps = false;
    protected $hidden = ['laravel_through_key'];
    protected $appends = ['wished'];


    protected $fillable = [
        'title',
        'description',
        'latitude',
        'longitude',
        'user_id',
        'municipal_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function municipal()
    {
        return $this->belongsTo(Municipal::class, 'municipal_id');
    }
    public function pictures()
    {
        return $this->hasMany(PlacePicture::class, 'place_id');
    }

    public function getMunicipalIdAttribute($value)
    {
        return Municipal::where("id", $value)->select("id", "name_fr", "state_id")->with(["state" => function ($query) {
            $query->select("id", "name_fr");
        }])->first();
    }
    public function getLatitudeAttribute($value)
    {
        return (float) $value;
    }
    public function getLongitudeAttribute($value)
    {
        return (float) $value;
    }
    public function getModelAttribute()
    {

        return  $this->model = "place";
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'place_id');
    }

    public function getWishedAttribute()
    {
        $user_id =  auth()->user()->id;
        $place_id = $this->attributes['id'];
        $wished = false;
        if (WishListItem::where([["user_id", $user_id], ["place_id", $place_id]])->exists()) {
            $wished = true;
        }
        return $this->attributes['wished'] = $wished;
    }

    public function tags()
    {
        return $this->hasManyThrough(
            Tag::class,
            PlaceTag::class,
            'place_id',
            'id',
            'id',
            'tag_id'
        );
    }


    public static  function add($jsonData, $files, $tags, $id)
    {
        $placeid =  DB::table('places')->insertGetId([
            'title' => $jsonData["title"],
            'description' => $jsonData["description"],
            'latitude' => $jsonData["latitude"],
            'longitude' => $jsonData["longitude"],
            'user_id' => $id,
            'municipal_id' => $jsonData["municipal_id"]
        ]);

        $pictures = [];
        foreach ($files as $file) {
            $pictures[] = cloudinary()->upload($file->getRealPath(), [
                'folder' => 'tahwisa/places/' . $placeid . '/',
                'format' => "webp",
            ])->getSecurePath();
        }
        foreach ($pictures as $k => $picture) {
            $arg = [];
            $arg['path'] = $picture;
            DB::table('places_pictures')->insert([
                'path' => $arg['path'], 'place_id' => $placeid
            ]);
        }

        foreach ($tags as $tag) {
            if (array_key_exists('id',$tag)) {
                PlaceTag::create([
                    'tag_id' => $tag['id'],
                    'place_id' => $placeid,
                ]);
            } elseif (array_key_exists('name',$tag)) {
                $newTag = Tag::create([
                    'name' => $tag['name'],
                ]);
                PlaceTag::create([
                    'tag_id' => $newTag->id,
                    'place_id' => $placeid,
                ]);
            }
        }
    }
}
