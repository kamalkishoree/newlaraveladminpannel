<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Log;
use PhpParser\Node\Expr\FuncCall;
use Unifonic;

trait smsManager{

    public function otpLessPhone($user)
    {
        $phone_number = $user->dial_code.$user->mobile;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'clientId' => env('OTPLESS_CLIENT_ID'),
            'clientSecret' => env('OTPLESS_SECRET_KEY'),
        ])->post('https://auth.otpless.app/auth/v1/initiate/otp', [
            'phoneNumber' => $phone_number,
            'expiry' => 600,
            'otpLength' => 4,
            'channels' => ['WHATSAPP', 'SMS'],
            'metadata' => [
                'key1' => 'Here is your OTP for Login in QUICK APP.',
             //   'key2' => 'Data2',
            ],
        ]);
        if ($response->failed()) {
            return $response;
        } else {
            return $response;
            $data = $response->body();
            return $data;
        }

    }

    public function otpLessVerify($request)
    {

        $request->validate([
            'unique_request_id' => 'required|string',
            'otp' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'clientId' => env('OTPLESS_CLIENT_ID'), // Use environment variables for sensitive data
            'clientSecret' => env('OTPLESS_SECRET_KEY'),
        ])->post('https://auth.otpless.app/auth/v1/verify/otp', [
            'requestId' => $request->input('unique_request_id'),
            'otp' => $request->input('otp'),
        ]);

        if ($response->failed()) {
           
            return false;
        }
        if( $response->status() == 200)
        {
            return true;

        }
        else{
            return false;
        }
    }
}

