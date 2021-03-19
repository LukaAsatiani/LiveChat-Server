<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\CustomResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller{
    use CustomResponse;    

    public function signup(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:8|max:64|regex:/(^([a-zA-Z0-9]+)$)/u|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8|max:64',
        ]);

        if ($validator->fails()) {
            return $this->respondWithValidationError($validator->errors()->messages(), 422, 'validation.fields');
        }

        $user = User::create([
            "username" => $request->input('username'),
            "email" => $request->input('email'),
            "password" => bcrypt($request->input('password'))
        ]);

        return $this->respond($user, 'auth.created', 201);
    }

    function login(Request $request){
        $user= User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->respondWithError('auth.failed', 401);
        }
        
        $token = $user->createToken('my-app-token')->plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $this->respond($response, "auth.loggedin", 201);;
    }
}