<?php

namespace App\Models;

use App\Models\PlacePicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Nyholm\Psr7\Request;

class Place extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use HasFactory;


    protected $hidden = ['laravel_through_key'];
    protected $appends = ['wished'];


    protected $fillable = [
        'title',
        'description',
        'latitude',
        'longitude',
        'user_id',
        'municipal_id',
        'status'
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
        $user_id = auth()->user()->id;
        $place_id = $this->attributes['id'];
        $wished = false;
        if (WishListItem::where([["user_id", $user_id], ["place_id", $place_id]])->exists()) {
            $wished = true;
        }
        return $this->attributes['wished'] = $wished;
    }

    public function getStatusAttribute($value)

    {
        return PlaceStatus::find($value)->name;
    }

    public function wishes()
    {

        return $this->hasMany(WishListItem::class, 'place_id', 'id');
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

    public function scopeApproved($query)
    {
        return $query->where('status', 2);
    }

    public static function add($jsonData, $files)

    {
        set_time_limit(500);
        $pictures = [];
        $cloudinary = cloudinary();
        foreach ($files as $file) {
            $pictures[] = $cloudinary->upload($file->getRealPath(), [
                'folder' => 'tahwisa/places/v2/',
                'quality' => "auto",
                'fetch_format' => "auto"
            ])->getSecurePath();
        }
        $tags = (array_key_exists("tags", $jsonData)) ? $jsonData['tags'] : [];
        $user = auth()->user();
        $placeId = Place::create([
            'title' => $jsonData["title"],
            'description' => $jsonData["description"],
            'latitude' => $jsonData["latitude"],
            'longitude' => $jsonData["longitude"],
            'user_id' => $user->id,
            'municipal_id' => $jsonData["municipal_id"],
            'status' => ($user->role == 1) ? 1 : 2
        ])->id;

        foreach ($pictures as $k => $picture) {
            $arg = [];
            $arg['path'] = $picture;
            DB::table('places_pictures')->insert([
                'path' => $arg['path'], 'place_id' => $placeId
            ]);
        }

        foreach ($tags as $tag) {
            if (array_key_exists('id',$tag)) {
                PlaceTag::create([
                    'tag_id' => $tag['id'],
                    'place_id' => $placeId,
                ]);
            } elseif (array_key_exists('name',$tag)) {
                $tagExists = Tag::where('name', $tag['name'])->first();
                if ($tagExists) {
                    PlaceTag::create([
                        'tag_id' => $tagExists->id,
                        'place_id' => $placeId,
                    ]);
                } else {
                    $newTag = Tag::create([
                        'name' => $tag['name'],
                    ]);
                    PlaceTag::create([
                        'tag_id' => $newTag->id,
                        'place_id' => $placeId,
                    ]);
                }
            }
        }

        $user = auth()->user();

        if ($user->role == 1) {

            $admins = User::whereIn('role', [2, 3])->pluck('id')->toArray();
            $adminsTokens = FcmToken::whereIn('user_id', $admins)->pluck('token')->toArray();
            $notification = Notification::create(['title' => "A new post has come", 'body' => "Check then approve or reject", 'description' => '', 'type' => 1,
                'place_id' => $placeId
            ]);
            foreach ($admins as $admin) {
                NotificationItem::create(['user_id' => $admin, 'notification_id' => $notification->id]);
            }
            try {
                FcmToken::send($adminsTokens, $notification);
            } catch (\Exception $e) {
                return "fcm_error";
            }
        }

    }

    public static function updatePlace($place, $jsonData, $files)

    {
        set_time_limit(500);
        if ($files == null ? false : count($files) > 0) {
            set_time_limit(500);
            $pictures = [];
            $cloudinary = cloudinary();
            foreach ($files as $file) {
                $pictures[] = $cloudinary->upload($file->getRealPath(), [
                    'folder' => 'tahwisa/places/v2/',
                    'quality' => "auto",
                    'fetch_format' => "auto"
                ])->getSecurePath();
            }
            PlacePicture::where('place_id', $place->id)->delete();

            foreach ($pictures as $k => $picture) {
                $arg = [];
                $arg['path'] = $picture;
                DB::table('places_pictures')->insert([
                    'path' => $arg['path'], 'place_id' => $place->id
                ]);
            }
        }

        $place->update(array_filter($jsonData));
        $place->status = 1;
        $place->save();

        $user = auth()->user();

        if ($user->role == 1) {

            $admins = User::whereIn('role', [2, 3])->pluck('id')->toArray();
            $adminsTokens = FcmToken::whereIn('user_id', $admins)->pluck('token')->toArray();
            $notification = Notification::create(['title' => "A post has been updated", 'body' => "Check then approve or reject", 'description' => '', 'type' => 1,
                'place_id' => $place->id
            ]);
            foreach ($admins as $admin) {
                NotificationItem::create(['user_id' => $admin, 'notification_id' => $notification->id]);
            }
            try {
                FcmToken::send($adminsTokens, $notification);
            } catch (\Exception $e) {
                return "fcm_error";
            }
        }

    }

    public static function available(Place $place)
    {
        return !($place->status == 'approved' || $place->status == 'refused');
    }

    public static function approve(Place $place)
    {
        $place->status = 2;
        $place->save();
        $adminsTokens = FcmToken::where('user_id', $place->user_id)->pluck('token')->toArray();
        $notification = Notification::create(['title' => "Your post has been approved", 'body' => "You can see your post now", 'description' => '',
            'type' => 2,
            'place_id' => $place->id
        ]);
        NotificationItem::create(['user_id' => $place->user_id, 'notification_id' => $notification->id]);
        try {
            FcmToken::send($adminsTokens, $notification);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function refuse(Place $place, $description, $messages)
    {
        $place->status = 3;
        $place->save();
        $adminsTokens = FcmToken::where('user_id', $place->user_id)->pluck('token')->toArray();
        $notification = Notification::create(['title' => "Your post has been refused", 'body' => "See what's going wrong", 'description' => $description,
            'type' => 3,
            'place_id' => $place->id
        ]);
        NotificationItem::create(['user_id' => $place->user_id, 'notification_id' => $notification->id]);
        foreach ($messages as $message) {
            NotificationRefuseMessageItem::create([
                'notification_id' => $notification->id,
                'message_id' => $message
            ]);
        }

        try {
            FcmToken::send($adminsTokens, $notification);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
