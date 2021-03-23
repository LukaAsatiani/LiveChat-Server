<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Traits\Messager;
use Illuminate\Support\Facades\Request;
use App\Traits\RoomTrait as Room;

class Rooms implements ShouldBroadcast{
    use Dispatchable, InteractsWithSockets, SerializesModels, Room;
    
    public $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function broadcastWith(){
        $room = $this->createRoomT($this->data['creator_id'], $this->data['title']);
        return ['room' => $room];
    }

    public function broadcastOn(){
        return new Channel('user_'.$this->data['creator_id']);
    }
}
