<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PhpParser\Node\Stmt\Return_;

class JWTToken {
    public static function createToken($userEmail):string{
        $key=env(key:'JWT_KEY');
        $payload=[
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userEmail'=>$userEmail
        ];
       return JWT::encode($payload,$key,alg:'HS256');
    }

    function VerifyToken($token){
        try{
            $key=env(key:'JWT_KEY');
            $decode=JWT::decode($token,new Key($key,algorithm:'HS256'));
            return $decode->userEmail;
        }
        catch(Exception $e){
            return 'Unauthorized';
        }
    }
}