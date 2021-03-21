<?php

namespace App\Traits;

use App\Models\RoomMessage;

trait Messager {
    public function sendRoomMessage($sender_id, $room_id, $content){
        $message = RoomMessage::create([
            "sender_id" => $sender_id,
            "room_id" => $room_id,
            "content" => $content
        ]);

        return $message;
    }
}