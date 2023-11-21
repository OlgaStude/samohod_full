<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class userController extends Controller
{

    public function register(Request $req)
    {

        
        try {
            
            if($req->paternname != null){
                $fields = $req->validate([
                    'name' => 'required|string|regex:/^[а-яА-Я][\pL\s\-]+$/u',
                    'surname' => 'required|string|regex:/^[а-яА-Я][\pL\s\-]+$/u',
                    'paternname' => 'regex:/^[а-яА-Я][\pL\s\-]+$/u',
                    'email' => 'required|email|string|unique:users',
                    'login' => 'required|string|regex:/^[a-zA-Z][\pL\s\-]+$/u|unique:users',
                    'password' => 'required|min:6|confirmed'
                ]);
                $fio = $fields['name'].' '.$fields['surname'].' '.$fields['paternname'];
            }else{
                $fields = $req->validate([
                    'name' => 'required|string|regex:/^[а-яА-Я][\pL\s\-]+$/u',
                    'surname' => 'required|string|regex:/^[а-яА-Я][\pL\s\-]+$/u',
                    'email' => 'required|email|string|unique:users',
                    'login' => 'required|string|regex:/^[a-zA-Z][\pL\s\-]+$/u|unique:users',
                    'password' => 'required|min:6|confirmed'
                ]);
                $fio = $fields['name'].' '.$fields['surname'];
            }

            
        } catch (ValidationException $e) {
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
                    "code" => 422,
                    'message' => 'Несоответствие требованиям',
                    'warnings' => $errors
                ]


            ], 422)->header('status', '422');
        }
        
        

        $user = User::create([
            'name' => $fio,
            'email' => $fields['email'],
            'login' => $fields['login'],
            'password' => Hash::make($fields['password']),
            'role' => 'client'
        ]);


        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'token' => $token,
        ];

        return response($response, 201)->header('status', '201');
    }


    public function login(Request $req)
    {


        try {
            $fields = $req->validate([
                'email' => 'required|string',
                'password' => 'required|string'
            ]);
        } catch (ValidationException $e) {
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
                    "code" => 422,
                    'message' => 'Несоответствие требованиям',
                    'warnings' => $errors
                ]


            ], 422)->header('status', '422');
        }

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'warning' => [
                    "code" => 401,
                    'message' => 'Ненеудачный вход',
                ]
            ], 401)->header('status', '401');
        }

        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'token' => $token
        ];

        return response($response, 200)->header('status', '200');
    }


    public function logout(Request $req)
    {

        auth()->user()->tokens()->delete();

        $response = [
            'content' => [
                'message' => 'Выход'
            ]
        ];

        return response($response, 200)->header('status', '200');
    }


    public function getOwnInfo(){
        $user = User::find(auth()->user()->id);

        return $user;
    }

    public function checkPassword(Request $req){
        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return response([
                'warning' => [
                    "code" => 401,
                    'message' => 'Неверный пароль',
                ]
            ], 401)->header('status', '401');
        }
    }
}
