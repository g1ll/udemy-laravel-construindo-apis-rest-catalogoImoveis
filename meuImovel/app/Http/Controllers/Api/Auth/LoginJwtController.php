<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginJwtController extends Controller
{
    public function login(Request $request){
        $credentials = $request->all(['email','password']);
        if(auth('api')->attempt($credentials)){
            $message = new ApiMessages("Unauthorized");
            return response()->json($message->getMessage(),401);
        }
    }
}
