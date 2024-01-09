<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;


class UserController extends Controller {
    function UserRegistration( Request $request ) {
        try {
            User::create( [
                'firstName' => $request->input( key: 'firstName' ),
                'lastName'  => $request->input( key: 'lastName' ),
                'email'     => $request->input( key: 'email' ),
                'mobile'    => $request->input( key: 'mobile' ),
                'password'  => $request->input( key: 'password' ),

            ] );
            return response()->json( [
                'status'  => 'success',
                'message' => 'User Registration Successful',
            ], status: 200 );
        } catch ( Exception $e ) {
            return response()->json( [
                'status'  => 'Failed',
                'message' => 'User Registration Failed',
            ], status: 200 );
        }

    }

    // function UserLogin( Request $request ) {
    //     $count = User::where( 'email', '=', $request->input( key: 'email' ) )
    //         ->where( 'password', '=', $request->input( key: 'password' ) )
    //         ->count();

    //     if ( $count == 1 ) {
    //         //user Login->JWT Token Issue
    //         $token = JWTToken::createToken( $request->input( key: 'email' ) );
    //         return response()->json( [
    //             'status'  => 'Success',
    //             'message' => 'User Login Successful',
    //             'token'   => $token,
    //         ], status: 200 );

    //     } else {
    //         return response()->json( [
    //             'status'  => 'Failed',
    //             'message' => 'User Registration Failed',
    //         ], status: 200 );
    //     }

    // }
    function UserLogin(Request $request)
{
    try {
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->count();

        if ($count == 1) {
            // User Login -> JWT Token Issue
            $token = JWTToken::createToken($request->input('email'));

            return response()->json([
                'status' => 'Success',
                'message' => 'User Login Successful',
                'token' => $token,
            ], 200);
        } else {
            throw new \Exception('User not found or invalid credentials.');
        }
    } catch (\Exception $e) {
        // Handle the exception
        return response()->json([
            'status' => 'Failed',
            'message' => $e->getMessage(),
        ], 200);
    }
}

    // function SendOTPCode( Request $request ) {
       
        
    //     $email = $request->input( key: 'email' );
    //     $otp = rand( 1000, 9999 );
    //     $count = User::where( 'email', '=', $email )->count();

    //     if ( $count == 1 ) {

    //         //OTP email Address
    //         Mail::to($email)->send(new OTPMail($otp));

    //         //Insert OTP code Database table
    //         User::where('email','=',$email)->update(['otp'=>$otp]);

    //         return response()->json( [
    //             'status'  => 'success',
    //             'message' => '4 Digit OTP has been send to your email',
    //         ], status: 200 );
    //     } else {
    //         return response()->json( [
    //             'status'  => 'failed',
    //             'message' => $e->getMessage(),
    //         ], status: 200 );
    //     }

    // }
    function SendOTPCode(Request $request)
    {
        try {
            $email = $request->input('email');
            $otp = rand(1000, 9999);
            $count = User::where('email', '=', $email)->count();
    
            if ($count == 1) {
                // OTP email Address
                Mail::to($email)->send(new OTPMail($otp));
    
                // Insert OTP code into the Database table
                User::where('email', '=', $email)->update(['otp' => $otp]);
    
                return response()->json([
                    'status' => 'success',
                    'message' => '4 Digit OTP has been sent to your email',
                ], 200);
            } else {
                throw new \Exception('User not found.');
            }
        } catch (\Exception $e) {
            // Handle the exception
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ], 200);
        }
    }

}
