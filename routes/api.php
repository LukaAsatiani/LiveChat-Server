<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('/broadcast/messages', [RoomController::class, 'sendMessage']);
    Route::post('/broadcast/rooms', [RoomController::class, 'createRoom']);
    Route::get('user', [UserController::class,'user'])->name('user');
    Route::get('users', [UserController::class,'usersAll']);
    Route::get('users/{id}', [UserController::class,'usersOne'])->where('room_id', '[0-9]+');
    Route::get('logout', [AuthController::class,'logout']);
    Route::post('rooms', [RoomController::class,'createRoom']);
    Route::get('rooms', [RoomController::class,'getRoomsList']);
    Route::post('room/user', [RoomController::class,'addUserToRoom']);
    Route::get('messages/{room_id}', [RoomController::class,'getRoomMessages'])->where('room_id', '[0-9]+');
});

Route::post('login', [AuthController::class,'login']);
Route::get('/unauth', [AuthController::class,'unauth'])->name('unauth');
Route::post('signup', [AuthController::class,'signup']);

Route::any('{any}', function(){
    return 'Route not found.';
});