<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use App\Events\Messages;
use App\Models\RoomUserConnector;
use Illuminate\Support\Facades\Validator;
use App\Traits\CustomResponse;
use App\Traits\Messager;
use App\Traits\RoomTrait;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller{
    use CustomResponse, Messager, RoomTrait;
    
    public function addUserToRoom(Request $request){
        Validator::extend('unique_multiple', function ($attribute, $value, $parameters, $validator){
            if (isset($validator->getData()['id'])) return true;
            $table = array_shift($parameters);
            $query = DB::table($table);
            foreach ($parameters as $i => $field){
                $query->where($field, $validator->getData()[$field]);
            }
            return ($query->count() == 0);
        });
        
        $validator = Validator::make($request->only('user_id', 'room_id'), [
            'user_id' => 'exists:users,id|unique_multiple:room_user_connectors,user_id,room_id',
            'room_id' => 'exists:rooms,id|unique_multiple:room_user_connectors,user_id,room_id'
        ]);
        
        if ($validator->fails()) {
            return $this->respondWithValidationError($validator->errors()->messages(), 422, 'validation.fields');
        }

        RoomUserConnector::create([
            "user_id" => $request->user_id,
            "room_id" => $request->room_id
        ]);

        return $this->respond(User::find($request->user_id)->rooms, 'room.user.added');
    }

    public function createRoom(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:32|regex:/(^([a-zA-Z0-9]+)$)/u',
        ]);

        if ($validator->fails()) {
            return $this->respondWithValidationError($validator->errors()->messages(), 422, 'validation.fields');
        }
        
        // broadcast(new Rooms([
        //     'title' => $request->title,
        //     'creator_id' => $request->user()->id
        // ]));
        $room = $this->createRoomT($request->user()->id, $request->title);

        return $this->respond($room, 'room.created');
    }

    public function getRoomsList(Request $request){
        return $this->respond($request->user()->rooms, 'room.list');
    }

    public function getRoomsAll(Request $request){
        return $this->respond($request->user()->all_rooms, 'room.list');
    }

    public function getRoomMessages(Request $request, $room_id){
        $room = Room::find($room_id);
        
        if($room)
            return $this->respond($room->messages, 'room.messages');
        else
            return $this->respondWithError('room.find', 404);
    }

    public function sendMessage(Request $request){
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|integer',
            'content' => 'required|max:32000'
        ]);

        if ($validator->fails()) {
            return $this->respondWithValidationError($validator->errors()->messages(), 422, 'validation.fields');
        }
        
        broadcast(new Messages([
            'sender_id' => $request->user()->id, 
            'room_id' => $request->room_id, 
            'content' => $request->content
        ]));
    }
}
