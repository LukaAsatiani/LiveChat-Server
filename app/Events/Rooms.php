<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Traits\Messager;
use Illuminate\Support\Facades\Request;
use App\Traits\RoomTrait;
use App\Models\Room;
use App\Models\RoomUserConnector;

class Rooms implements ShouldBroadcast{
    use Dispatchable, InteractsWithSockets, SerializesModels, RoomTrait;
    
    public $user_id, $sort, $list = [];

    public function __construct($user_id, $sort = false){
        $this->user_id = $user_id;
        $this->sort = $sort;
    }

    public function broadcastWith(){
        if($this->sort === true)
            $connectors = RoomUserConnector::where('user_id', $this->user_id)
                ->orderBy('updated_at', 'desc')
                ->get();
        else {
            $connectors = RoomUserConnector::where('user_id', $this->user_id)->get();
        }

        foreach($connectors as $index => $connector){
            // $this->list[$index] = $connector->getAttribute('room_id');
            $this->list[$index] = Room::where('id', $connector->getAttribute('room_id'))
                ->with('connector', function ($query) {
                    $query->where('user_id', 1);
                })
                ->with('last_message', function ($query) {
                    $query->orderBy('updated_at', 'desc'); 
                }) 
                ->first();
        }
        
        return ['data' => $this->list];
    }

    public function broadcastOn(){
        return new Channel('user_'.$this->user_id);
    }
}
