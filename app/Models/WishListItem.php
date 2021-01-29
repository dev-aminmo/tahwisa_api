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



}