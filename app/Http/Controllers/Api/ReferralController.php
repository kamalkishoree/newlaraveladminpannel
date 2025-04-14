<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\WalletTrait;
use Illuminate\Support\Facades\Log;
use App\Models\{Wallet,Transaction};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class ReferralController extends Controller
{
    use WalletTrait;

    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function generateReferralCode(Request $request): JsonResponse
    {
        $user = $request->user();
  
        if (!$user->referral_code) {
            $user->update([
                'referral_code' => $this->referralService->generateReferralCode()
            ]);
        }
        return response()->json([
            'referral_code' => $user->referral_code,
            'referral_url' => $request->root().'/api/referral-download?code='.$user->referral_code
        ]);
    }

    public function applyReferralCode(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'referral_code' => 'required|string|exists:users,referral_code'
        ]);
        if ($validator->fails()) {
        
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 400);
        }
    
        $user = $request->user();
        $referrer = User::where('referral_code', $request->referral_code)->first();

      
        if ($user->id === $referrer->id) {
            return response()->json([
                'message' => 'You cannot refer yourself'
            ], 400);
        }

        $existingReferral = Referral::where('referred_id', $user->id)->exists();
        if ($existingReferral) {
            return response()->json([
                'message' => 'You have already used a referral code'
            ], 400);
        }

        $referral = $this->referralService->createReferral($referrer, $user);

        return response()->json([
            'message' => 'Referral code applied successfully',
            'referral' => $referral
        ],200);
    }

    public function getReferralStats(Request $request): JsonResponse
    {
        $user = $request->user();
        $referrals = Referral::where('referrer_id', $user->id)
            ->with('referred:id,name,email,last_name,image')
            ->get();

        return response()->json([
            'referral_code' => $user->referral_code,
            'referral_balance' => $user->referral_balance,
            'total_referrals' => $referrals->count(),
            'completed_referrals' => $referrals->where('status', 'completed')->count(),
            'pending_referrals' => $referrals->where('status', 'pending')->count(),
            'referrals' => $referrals,
            
        ]);
    }

    public function referralToWallet(Request $request)
    {
        try {
            $user = $request->user();
            if ($user->referral_balance <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient referral balance',
                ], 400);
            }
            $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);
            $wallet->increment('withdrawn_balance', $user->referral_balance);
            $user->decrement('referral_balance',$user->referral_balance);
            $transactionId = 'referral_transax_' . Str::uuid();
            Transaction::createTransaction($user->id, $user->referral_balance, 'credit', 'referral', 'approved', $transactionId);
            $data = [
                'success' => true,
                'message' => 'Referral amount added successfully',
                'transaction_id' => $transactionId,
                'wallet_balance' => $wallet->withdrawn_balance
                ];
        } catch (\Exception $e) {
            \Log::error('Error in referralToWallet: ' . $e->getMessage());
             $data =[
                'success' => false,
                'message' => 'Failed to process referral amount',
                'error' => $e->getMessage()
              ];
        }
          return $data ;
    }

     public function refferalHandle(Request $request )
    {
        try {
            $code = $request->code ? $request->code : 'quicks';
            $encodedData = base64_encode("quicks://".$code);
            $encodedUrl =$request->root().'/'.$encodedData;
            $deepLink = base64_decode($encodedUrl);
            $userAgent = $request->header('User-Agent');
            $isAndroid = stripos($userAgent, 'Android') !== false;
            $isIOS = preg_match('/iPhone|iPad|iPod|Macintosh/i', $userAgent);
            $fallbackUrl = $isAndroid
                ? env('PLAY_STORE_URL')
                : ($isIOS ? env('APP_STORE_URL') : $request->root());


            return response()->make("
                <html>
                    <head>
                        <title>Redirecting...</title>
                        <script>
                            var appLink = '{$deepLink}';
                            var fallbackUrl = '{$fallbackUrl}';
                            var hasFocus = true;
                            var isRedirecting = false;

                            document.addEventListener('visibilitychange', function() {
                                if (document.hidden) {
                                    hasFocus = false;
                                }
                            });

                            window.addEventListener('focus', function() {
                                isRedirecting = true;
                            });

                            window.location.href = appLink;

                            setTimeout(function() {
                                if (hasFocus && !isRedirecting) {
                                    window.location.href = fallbackUrl;
                                }
                            }, 3000);
                        </script>
                    </head>
                    <body>
                        <p>If you are not redirected, <a href=\"{$fallbackUrl}\">click here</a>.</p>
                    </body>
                </html>
            ", 200, ['Content-Type' => 'text/html']);

        } catch (\Throwable $e) {
            Log::error("Linking Error: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

} 