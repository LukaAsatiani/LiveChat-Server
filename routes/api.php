<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Events\Chats;
use App\Http\Controllers\UserController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/broadcast', function (Request $request) {
    broadcast(new Chats("hello, bro"));
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post("user",[UserController::class,'user']);
    Route::post("users",[UserController::class,'usersAll']);
    Route::post("users/{id}",[UserController::class,'usersOne']);
});

Route::post("login",[UserController::class,'login']);
Route::post("signup",[UserController::class,'signup']);