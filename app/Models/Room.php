<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model{
    use HasFactory;

    protected $fillable = [
        'title',
        'creator_id'
    ];

    protected $attributes = [
        
    ];

    public function messages(){
        return $this->hasMany(RoomMessage::class, 'room_id')->with('sender')->latest()->limit(50)->orderBy('id', 'desc');
    }

    public function last_message(){
        return $this->hasOne(RoomMessage::class, 'room_id')->with('sender');
    }

    public function connector(){
        return $this->hasMany(RoomUserConnector::class, 'room_id');
    }
}
