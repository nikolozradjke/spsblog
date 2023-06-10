<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('email', $request->email)->first();

        if(!$user && !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'თქვენი შეყვანილი მონაცემები არ ემთხვევა ჩვენს ხელთ არსებულს!'
            ], 401);
        }

        $token = $user->createToken('login_token')->plainTextToken;

        return response()->json([
            'message' => 'Success',
            'token' => $token
        ], 200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Success'
        ], 200);
    }
}
