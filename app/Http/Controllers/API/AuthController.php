<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{   
    /**
     * Register user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /* public function register(Request $request)
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
    } */

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            return response()->json([
                'token' => $user->createToken('ApiPassToken')->accessToken,
            ], 201); 
        } 
        else{ 
            return response()->json(['message'=>'Email e senha inválido!'], 401); 
        } 
    }

    /**
     * Logout api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();       
        
        return response()->json([
            'message' => 'Successfully logged out!'
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
