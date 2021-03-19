<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

trait CustomResponse {
    protected $options = JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES;

    protected function respond($data, $message_uri = null, $http_code = 200, $replace = []){
        return response()->json([
            'success' => true,
            'status' => $http_code,
            'data' => $data,
            'message' => $message_uri ? Lang::get($message_uri, $replace) : ''
        ], $http_code, [], $this->options);
    }

    protected function respondWithMessage($message_uri, $http_code = 200, $replace = []){
        return response()->json([
            'success' => true,
            'status' => $http_code,
            'message' => Lang::get($message_uri, $replace)
        ], $http_code, [], $this->options);
    }

    protected function respondWithError($error_uri, $http_code, $replace = []){
        return response()->json([
            'success' => false,
            'status' => $http_code,
            'message' => Lang::get($error_uri, $replace)
        ], $http_code, [], $this->options);
    }

    protected function respondWithToken($token, $message_uri){
        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => Lang::get($message_uri),
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200, [], $this->options);
    }

    protected function respondWithValidationError($error_uri, $http_code, $message_uri, $replace = []){
        $messages = [];

        foreach($error_uri as $key => $val){
            $messages[$key] = Lang::get($val[0], $replace);
        }
        
        return response()->json([
            'success' => false,
            'status' => $http_code,
            'errors' => $messages,
            'message' => $message_uri
        ], $http_code, [], $this->options);
    }
}