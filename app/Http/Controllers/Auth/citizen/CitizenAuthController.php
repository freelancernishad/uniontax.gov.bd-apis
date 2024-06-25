<?php

namespace App\Http\Controllers\Auth\citizen;


use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Citizen;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class CitizenAuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:citizens',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }


        $citizen = new Citizen([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $citizen->save();
        $payload = [
            'name'=>$citizen->name,
            'email'=>$citizen->email,
        ];

        $token = JWTAuth::fromUser($citizen);

        return response()->json(['message' => 'Citizen registered successfully', 'token' => $token, 'payload' => $payload], 201);
        // Return a response or redirect
    }

    public function login(Request $request)
    {
          $credentials = $request->only('email', 'password');

        if (Auth::guard('citizen')->attempt($credentials)) {
            $citizen = Auth::guard('citizen')->user();
            $token = JWTAuth::fromUser($citizen);
            // $token = $citizen->createToken('access_token')->accessToken;

            $payload = [
                'name'=>$citizen->name,
                'email'=>$citizen->email,
            ];

            return response()->json(['token' => $token,'payload' => $payload]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function checkTokenExpiration(Request $request)
{
    // $token = $request->token;
    $token = $request->bearerToken();

    try {
        // Check if the token is valid and get the authenticated citizen
        $user = Auth::guard('citizen')->setToken($token)->authenticate();

        // Check if the token's expiration time (exp) is greater than the current timestamp
        $isExpired = JWTAuth::setToken($token)->checkOrFail();

        return response()->json(['message' => 'Token is valid', 'citizen' => $user], 200);
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        // Token has expired
        return response()->json(['message' => 'Token has expired'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        // Token is invalid
        return response()->json(['message' => 'Invalid token'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        // Token not found or other JWT exception
        return response()->json(['message' => 'Error while processing token'], 500);
    }
}




public function logout(Request $request)
{
    try {
        $token = $request->bearerToken();
        if ($token) {
            JWTAuth::setToken($token)->invalidate();
            return response()->json(['message' => 'Logged out successfully']);
        } else {
            return response()->json(['message' => 'Invalid token'], 401);
        }
    } catch (JWTException $e) {
        return response()->json(['message' => 'Error while processing token'], 500);
    }
}

    public function checkToken(Request $request)
    {
        $citizen = $request->user('citizen');
        if ($citizen) {
            return response()->json(['message' => 'Token is valid']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


}
