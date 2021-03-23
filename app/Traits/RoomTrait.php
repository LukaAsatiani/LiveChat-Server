<?php

namespace App\Traits;

use App\Models\RoomMessage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RoomUserConnector;
use App\Models\Room;

trait RoomTrait {
    public function createRoomT($creator_id, $title){
        $room = Room::create([
            "title" => $title,
            "creator_id" => $creator_id
        ]);

        RoomUserConnector::create([
            "user_id" => $creator_id,
            "room_id" => $room->id
        ]);
        
        return $room;
    }
}