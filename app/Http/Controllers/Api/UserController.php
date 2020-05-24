<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;

class UserController extends Controller
{
    public function __construct(){
        //$this->middleware(['isAdmin'])->only('users');
    }

    /**
     * List users.
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        $users = User::all(); 
        return response()->json(['users' => $users], 201); 
    }

    /**
     * Register user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            //'password' => 'required|string|min:8|confirmed'
            'password' => 'required|string|min:8'
        ]);   
        if($validator->fails()){
            return response()->json(['message'=> $validator->errors()], 422);       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
   
        return response()->json([
            'message' => 'Successfully created user!',
        ], 201);
    }

    /**
     * User api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $user->roles;
        return response()->json(['user'=>$user], 201); 
    }
}