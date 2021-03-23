<?php

namespace App\Traits;

use App\Models\RoomMessage;
use App\Models\Room;

trait Messager {
    public function sendRoomMessage($sender_id, $room_id, $content){
        $message = RoomMessage::create([
            "sender_id" => $sender_id,
            "room_id" => $room_id,
            "content" => $content
        ]);
        
        $m = $message->sender;
        return array_merge($message->getAttributes(), ['sender' => $m->getAttributes()]);
    }
}