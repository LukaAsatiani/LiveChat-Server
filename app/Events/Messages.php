<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Traits\Messager;

class Messages implements ShouldBroadcast{
    use Dispatchable, InteractsWithSockets, SerializesModels, Messager;
    
    public $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function broadcastWith(){
        $message = $this->sendRoomMessage($this->data['sender_id'], $this->data['room_id'], $this->data['content']);
        return ['message' => $message];
    }

    public function broadcastOn(){
        return new Channel('chats');
    }
}
