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
        'unread_count' => 0
    ];

    public function messages(){
        return $this->hasMany(RoomMessage::class, 'room_id')->with('sender');
    }
}
