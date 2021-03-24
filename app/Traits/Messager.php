<?php

namespace App\Traits;

use App\Models\RoomMessage;
use App\Models\Room;
use App\Models\RoomUserConnector;

trait Messager {
    public function sendRoomMessage($sender_id, $room_id, $content){
        $message = RoomMessage::create([
            "sender_id" => $sender_id,
            "room_id" => $room_id,
            "content" => $content
        ]);
        
        RoomUserConnector::where('room_id', $room_id)->increment('unread_count');

        $m = $message->sender;
        return array_merge($message->getAttributes(), ['sender' => $m->getAttributes()]);
    }
}