<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_picture',
        'provider_name',
        'provider_id'
    ];

    public function wishes()
    {

        return $this->hasMany(WishListItem::class,'user_id');
    }
    /*    public function wishes()
    {
        return $this->hasMany(WishListItem::class);
    }*/

   /* public function wished_places()
    {
        return $this->belongsToMany(Place::class, 'wishlist', 'user_id', 'place_id');
    }*/
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getProfilePictureAttribute($value)
    {
        if($value == null){

            return "https://res.cloudinary.com/dtvc2pr8i/image/upload/w_150,f_auto/v1627577895/myballot/users/user_znc23a.png";

        }
        return $value;

    }
}
