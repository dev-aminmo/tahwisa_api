<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishListItem extends Model
{
    use HasFactory;
    protected $table="wishlist";
    protected $fillable = [
        'user_id',
        'place_id'
    ];

    public $timestamps = false;
    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }
    public function place(){
        return $this->belongsTo(Place::class,"place_id");
    }
    public function place_response(){
        return $this->place()->with(['pictures'=> function ($query){
            $query->select(//
                'path',
                'place_id'
            );
        }])->with(['tags'=>function($query){
            $query->select(['tag_id','name']);
        }])->withAvg('reviews','vote')->withCount('reviews')->with(['user'=>function($query){
            $query->select(['id','username','profile_picture']);}])->get();
    }

}
