<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Events\Messages;
use Illuminate\Support\Facades\Validator;
use App\Traits\CustomResponse;
use App\Traits\Messager;

class RoomController extends Controller{
    use CustomResponse, Messager;
    
    public function createRoom(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:32|regex:/(^([a-zA-Z0-9]+)$)/u',
        ]);

        if ($validator->fails()) {
            return $this->respondWithValidationError($validator->errors()->messages(), 422, 'validation.fields');
        }
        
        Room::create([
            "title" => $request->input('title'),
            "creator_id" => $request->user()->id
        ]);

        return $this->respondWithMessage('room.created', 201);
    }

    public function getRoomsList(Request $request){
        return $this->respond($request->user()->rooms, 'room.list');
    }

    public function getRoomMessages(Request $request, $room_id){
        $room = Room::find($room_id);
        
        if($room)
            return $this->respond($room->messages, 'room.messages');
        else
            return $this->respondWithError('room.find', 404);
    }

    public function createRoomMessage(Request $request){
        $this->sendRoomMessage($request->user()->id, 1, "Hello, Bro");
    }

    public function sendMessage(Request $request){
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|integer',
            'content' => 'required|max:32000'
        ]);

        if ($validator->fails()) {
            return $this->respondWithValidationError($validator->errors()->messages(), 422, 'validation.fields');
        }
        
        if($request->room_id === $request->user()->id){
            $this->sendRoomMessage($request->user()->id, $request->room_id, $request->content);
        } else {
            broadcast(new Messages([
                'sender_id' => $request->user()->id, 
                'room_id' => $request->room_id, 
                'content' => $request->content
            ]));
        }
    }
}
