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
use App\Http\Traits\smsManager;
use App\Models\Otp;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
class UserAuthController extends Controller
{

    use smsManager;

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
            $user->quick_id  = generateQuickId();
            
            if($user->save())
            {
                $sendotp = $this->sendOtp($user);
                if(!$sendotp)
                {
                    return response()->json(['error'=>'unabe to send otp', 401]);

                }
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
            //   pr($user);
              $token = '';
              $is_otp_varified = $this->varifyOtp($user,$request->otp);
              $Setting = Setting::where('id','!=',NULL)->first();
              $static_otp = $Setting->static_otp ? $Setting->static_otp:0;
              if($is_otp_varified || ($request->otp == 1111 &&  $static_otp == 1))
              {
                $token = $user->createToken('access_token')->accessToken;
                $user->access_token = $token;
                if($request->has('device_token') && !empty($request->device_token))
                {
                   \Log::info($request->all());
                   UserDevice::updateOrCreate(
                    ['device_token' => $request->device_token], // Unique condition
                    ['user_id' => $user->id]                    // Values to update or insert
                  );
                }
                $user->save();
               
                return response()->json([
                    'message' => 'Login Successfuly',
                    'user' =>$user,
                    'token' =>$token
                   ], 200);
               }
               else{
                 return response()->json([
                    'message' => 'Invalid OTP .',
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
               $user = User::where('mobile',$request->phone_number)->where('dial_code',$request->dial_code)->withTrashed()->first();
              
               if($user)
               {

                if($user->deleted_at)
                {
                    return response()->json([
                        'error' => 'Your account is deleted. Please contact support.',
                        'is_new' => false,
                    ], 403);
                }
                 $sendOtp = $this->sendOtp($user);

                 return response()->json([
                    'success' => 'OTP sent on registered mobile number',
                    'is_new' => false],
                     200);
                }
               else{
                 return response()->json([
                    'success' => 'No user registered with this number.',
                    'is_new' => true,
                    
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
    public function logout(Request $request)
    {

        //auth()->logout();
        $request->user()->token()->revoke(); //pasport
        $userDevice = UserDevice::where('device_token', $request->device_token)->first();
        if($userDevice)
        {
            $userDevice->delete();
        }
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


      public function userProfile(Request $request)
      {
        return response()->json(auth()->user());
      }

      public function editProfile(Request $request)
      {
         
             // Validate incoming request
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email'  => 'required|string|email',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                    $errors['error'] = __($error_value[0]);
                    return response()->json($errors, 422);
                }
            }

            $file = $request->file('image');
            $url = '';
            $user = Auth::user();
            if($user)
            {
             if(!is_null($file))
               {
                
                   // Generate a unique filename
                   $fileName = time() . '_' . $file->getClientOriginalName();
                   // Store the file in AWS S3
                   $path = $file->storeAs('website/user/images', $fileName, [
                    'disk' => 's3',
                    'visibility' => 'public'
                ]);    
                   // Make the file publicly accessible
                   $url = Storage::disk('s3')->url($path);
                
              }
                  $user->update([
                        'email' =>$request->email,
                        'name' =>$request->first_name,
                        'last_name' =>$request->last_name,
                        'image' => $url
                   ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'File uploaded successfully',
                        'user' => $user,
                    ], 200);

              }
              else
              {
                return response()->json([
                    'error' => true,
                    'message' => 'Invalid user',
                ], 200);
              }
        }
    
        public function sendOtp($user)
        {
            $otp_response =  $this->otpLessPhone($user);
            
            if($otp_response->status() == 200)
            {
                $response =  $otp_response->json();
                $updateUser =  $user->update([
                    'otp_request_id' =>$response['requestId']
                ]);
              
                if($updateUser)
                {
                    return  1 ;

                }
                else{
                    return 0;
                }
            }
            else{
                return $otp_response->body();
            }
        }

        public function varifyOtp($user,$otp)
        {

            if($otp != NULL)
            {
                $request = new Request(['otp'=>$otp,'unique_request_id'=>$user->otp_request_id]);
                $verify_otp =  $this->otpLessVerify($request);
                return $verify_otp;
            }

            
        }
        public function deleteAccount(Request $request)
        {
            $user = Auth::user();
            if($user)   
            {
                $user->delete();
                return response()->json(['message' => 'Account deleted successfully']);
            }
            else{
                return response()->json(['message' => 'Invalid user']);
            }
        }
 }

