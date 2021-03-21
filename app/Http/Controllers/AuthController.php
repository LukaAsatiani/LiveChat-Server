<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\CustomResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller{
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
            "password" => Hash::make($request->input('password')),
            "remember_token" => Str::random(21)
        ]);

        return $this->respond($user, 'auth.signedup', 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->respondWithValidationError($validator->errors()->messages(), 422, 'validation.fields');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->respondWithError('auth.failed', 401);
        }

        $token = $user->createToken('AuthToken')->plainTextToken;

        return $this->respond(['token' => $token], 'auth.loggedin');
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return $this->respondWithMessage('auth.loggedout', 200);
    }

    public function unauth(Request $request){
        return $this->respondWithError('auth.unauthorized', 401);
    }
}