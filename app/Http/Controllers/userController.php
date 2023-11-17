<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class userController extends Controller
{
    
    public function register(Request $req){

        try{

            $fields = $req->validate([
                'fio' => 'required|string',
                'email' => 'required|email|string|unique:users',
                'password' => 'required|min:8'
            ]);
        }catch(ValidationException $e){            
                $errors = [];
                foreach ($e->errors() as $err => $messages) {
                    foreach ($messages as $message) {
                    $errors[] = [
                        $err => $message,
                    ];
                }
            }
            return response()->json([
                'warning' => [
                    "code"=> 422,
                    'message'=> 'Несоответствие требованиям',
                    'warnings' => $errors
                ]
        
            
            ], 422)->header('status', '422');

        }
        

        $user = User::create([
            'name' => $fields['fio'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'role' => 'client'
        ]);
        

        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'token' => $token,
        ];

        return response($response, 201)->header('status', '201');

    }


    public function login(Request $req){

        try{
            $fields = $req->validate([
                'email' => 'required|string',
                'password' => 'required|string'
            ]);
        }catch(ValidationException $e){            
                $errors = [];
                foreach ($e->errors() as $err => $messages) {
                    foreach ($messages as $message) {
                    $errors[] = [
                        $err => $message,
                    ];
                }
            }
            return response()->json([
                'warning' => [
                    "code"=> 422,
                    'message'=> 'Несоответствие требованиям',
                    'warnings' => $errors
                ]

            
            ], 422)->header('status', '422');

        }

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'warning' => [
                    "code"=> 401,
                    'message'=> 'Ненеудачный вход',
                ]
            ], 401)->header('status', '401');
        }

        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'token' => $token
        ];

        return response($response, 200)->header('status', '200');

    }


    public function logout(Request $req) {
        
        auth()->user()->tokens()->delete();

        $response = [
            'content'=>[
                'message' => 'Выход'
            ]
        ];

        return response($response, 200)->header('status', '200');

    }
    
}
