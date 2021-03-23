<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMessage extends Model{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'sender_id',
        'content'
    ];

    public function sender(){
        return $this->hasOne(User::class, 'id', 'sender_id')->select('id', 'username');
    }
    
    public function m_sender(){
        return $this->hasOne(User::class, 'id', 'sender_id')->get();
    }
}
