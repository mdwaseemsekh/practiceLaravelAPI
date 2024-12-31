<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    public function show(){
        $users = User::all();
        return response()->json(["status" => "Success",
        "data" => $users]);
    }

    public function addUser(Request $request){
        $rules = [
            "name" => "required|min:3",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6",
        ];

        $value = $request->all();
        $validator = Validator::make($value,$rules);

        if($validator->fails()){
            $result = array(
            "status" => false,
            "massage" => "Error Occured",
            "error" => $validator->errors(),
            );
            return response()->json($result,400);
        }else{
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        if($user->save()){
            $result = [ "status" => true,
            "massage" => "User Created Successfully",];
        }

        
        return response()->json($result,200);
        }
    }

    public function updateUser(Request $request){
        $user = User::find($request->id);
       
         $user->name = $request->name;
        $user->email = $request->email;
        if($user->save())
        {
            return response()->json(["status" => "success"]);
        }
        else{
            return response()->json(["status" => "failed"]);
        }

    }
}
