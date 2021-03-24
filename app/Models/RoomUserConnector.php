<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomUserConnector extends Model{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'room_id',
    ];

    protected $attributes = [
        'unread_count' => 0  
    ];

    public function rooms(){
        return $this->hasMany(Room::class, 'id', 'room_id');
    }
}
