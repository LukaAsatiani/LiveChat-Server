<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rooms(){
        return $this->hasManyThrough(Room::class, RoomUserConnector::class, 'user_id', 'id', 'id', 'room_id')->orderBy('created_at', 'asc');;
    }
}
