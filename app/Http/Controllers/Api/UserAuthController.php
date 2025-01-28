<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UserAuthResouce;
use App\Http\Requests\Api\UserPhoneAuthResouce;
use App\Http\Requests\Api\VerifyOtpRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAuthController extends Controller
{

    public function register(StoreUserRequest $request) {
// Get validated data
        $validated = $request->validated();       
        if($validated)
        {
            $user = new User;
            $user->name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->dial_code = $request->dial_code;
            $user->mobile = $request->phone_number;
            $user->password = Hash::make($request->password??($request->first_name.$request->phone_number));
            $user->joining_referal = $request->joining_referal;
            $otp = rand(1000,9999);
            $user->phone_otp = $otp;
            if($user->save())
            {
                return response()->json($user, 201);
            }
        }
    }

    public function verifyOtp(VerifyOtpRequest $request) {
        $validated = $request->validated();       

        if($validated)
        {
            if($request->has('phone_number') && $request->has('dial_code') )
            {
              $user = User::where('mobile',$request->phone_number)->where('dial_code',$request->dial_code)->first();
              $token = '';
               if($user->phone_otp == $request->otp)
               {
                $token = $user->createToken('access_token')->accessToken;
                return response()->json([
                    'message' => 'Login Successfuly',
                    'user' =>$user,
                    'token' =>$token
                   ], 200);
               }
               else{
                 return response()->json([
                    'message' => 'OTP miss-matched.',
                 ], 401);
               }
              }
           }

        else{
            return response()->json(['error' => 'Invalid Request'], 400);

        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
     $validated = $request->validated();
     if($validated)
     {
        $user = User::where('mobile',$request->phone_number)->where('dial_code',$request->dial_code)->first();
           if($user->phone_otp == $request->otp)
           {
                $token =  $user->createToken('access_token')->accessToken;
                return response()->json([
                    'success' => true,
                    'statusCode' => 200,
                    'message' => 'User has been logged successfully.',
                    'data' => $user,
                    'token' =>$token
                ], 200);
            } else {
                return response()->json([
                    'success' => true,
                    'statusCode' => 401,
                    'message' => 'Unauthorized.',
                    'errors' => 'Unauthorized',
                ], 401);
            }
         }
              
     
    }
  


    public function checkNumberExist(UserPhoneAuthResouce $request)
    {
        if($request)
        {
            if($request->has('phone_number') && $request->has('dial_code') )
            {
               $user = User::where('mobile',$request->phone_number)->where('dial_code',$request->dial_code)->first();
               if($user)
               {
                return response()->json(['error' => 'This number is already registred with an account.','is_new' => false], 200);
               }
               else{
                 return response()->json([
                    'success' => 'No user registered with this number.',
                    'is_new' => true

                 ], 200);

               }
            }
            else{
                return response()->json(['error' => 'Invalid Request'], 400);

            }
        }
    }
  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
  
        return response()->json(['message' => 'Successfully logged out']);
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
        ];
    }


}
