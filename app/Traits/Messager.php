<?php

namespace App\Traits;

use App\Models\RoomMessage;
use App\Models\Room;
use App\Events\Rooms;
use App\Models\RoomUserConnector;

trait Messager {
    public function sendRoomMessage($sender_id, $room_id, $content){
        $message = RoomMessage::create([
            "sender_id" => $sender_id,
            "room_id" => $room_id,
            "content" => $content
        ]);
        
        RoomUserConnector::where('room_id', $room_id)
            ->where('user_id', '<>', $sender_id)
            ->increment('unread_count');
        
        $user_connectors = RoomUserConnector::where('unread_count', '>', 0)->select('user_id')->distinct()->get();
        
        foreach($user_connectors as $connector){
            broadcast(new Rooms($connector->getAttribute('user_id'), true));
        }

        $m = $message->sender;
        return array_merge($message->getAttributes(), ['sender' => $m->getAttributes()]);
    }
}