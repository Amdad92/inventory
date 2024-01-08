<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function UserRegistration(Request $request)
    {
        try{
            User::create([
                'firstName' => $request->input(key: 'firstName'),
                'lastName' => $request->input(key: 'lastName'),
                'email' => $request->input(key: 'email'),
                'mobile' => $request->input(key: 'mobile'),
                'password' => $request->input(key: 'password'),
    
    
            ]);
            return response()->json([
                'status'=>'success',
                'message'=>'User Registration Successful'
            ], status:200);
        }
        catch(Exception $e){
            return response()->json([
                'status'=>'Failed',
                'message'=>'User Registration Failed'
            ], status:200);
        } 
        
    }
    function UserLogin(Request $request){
        $count=  User::where('email','=',$request->input(key:'email'))
          ->where('password','=',$request->input(key:'password'))
          ->count();
          if($count==1){
              //user Login->JWT Token Issue
              $token=JWTToken::createToken($request->input(key:'email'));
              return response()->json([
                  'status'=>'Success',
                  'message'=>'User Login Successful',
                  'token'=>$token
              ], status:200);

          }else{
              return response()->json([
                  'status'=>'Failed',
                  'message'=>'User Registration Failed'
              ], status:200);
          }
      }

    
}
