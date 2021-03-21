<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\CustomResponse;
use App\Models\Room;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Factory as Auth;

class UserController extends Controller{
    use CustomResponse;

    protected $auth;
    
    public function __construct(Auth $auth){
        $this->auth = $auth;
    }

    public function user(Request $request){
        return $this->respond($request->user());
    }

    public function usersAll(Request $request){
        return $this->respond(User::all());
    }
    
    public function usersOne(Request $request){
        return $this->respond(User::find($request->id));
    }
}