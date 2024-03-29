<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipal extends Model
{
    use HasFactory;
    protected $table="municipales";

    public function state(){
        return $this->belongsTo(State::class,'state_id');
    }
}
