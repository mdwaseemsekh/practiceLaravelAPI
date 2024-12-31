<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
   public function register(Request $request){
   $rules = [
    "name" => "required|min:3",
    "email" => "required|min:3",
    "password" => "required|min:3|confirmed",
   ];

   $validation = Validator::make($request->all(),$rules);
   if($validation->fails()){
    return response()->json([
        "status" => false,
        "msg"=> "some errors occured",
        "errors"=>$validation->errors()
    ],400);
   }
   else{
    $data = $request->all();
    $user = User::create($data);
    $token = $user->createToken("myApp")->plainTextToken;
    $username = $user->name;

    return response()->json([
        "status" => true,
        "msg" => "user Created Successfully",
        "token" => $token,
        "username" =>$username,

    ],201);
   }
   }

   public function login(Request $request){
    $rules = [
        "email" => "required",
        "password" => "required"
    ];
    $inputs = $request->all();
    $validator = Validator::make($inputs,$rules);
    if($validator->fails()){
        return response()->json([
            "status" => false,
            "msg" => "Validation Error",
            "Errors" => $validator->errors(),
        ]);


    }
    
    $user = User::where('email',$request->email)->first();
   
    $passwordCheck = Hash::check($request->password,$user->password);
    
    if($user && $passwordCheck){
       $token =  $user->createToken('loginToken')->plainTextToken;
       return response()->json([
        "status" => true,
        "msg" => "login success",
        "token" => $token,
        
       ]);
    }else{
        return response()->json([
            "status" => false,
            "msg" => "Invalid Email OR Password",
            
        ],401);
    }
   }

   public function logout(Request $request){
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        "status" => "logout successfully",
    ]);
   }  
}

