<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password'])
        ]);

        $token = $user->createToken('samaToken')->plainTextToken;

        $response = [
            'user'=> $user,
            'token' => $token
        ]; 
        return response($response, 201);
    }

    public function login(Request $request){
        // auth()->user()->tokens()->delete();
        // return auth()->user()->tokens();
        
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        //check email
        $user = User::where('email', $fields['email'])->first();
        $user->tokens()->delete();
        

        //check password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            $response = [
                'Bad credentialds'
            ];
            return response($response, 401);
        };
        
        $token = $user->createToken('samaToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response($response, 200);
    }


    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out'
        ];

    }
}
