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
            'referral_url' => $request->root().'/referral-download?code='.$user->referral_code
        ]);
    }

    public function applyReferralCode(Request $request): JsonResponse
    {
        $request->validate([
            'referral_code' => 'required|string|exists:users,referral_code'
        ]);

        $user = $request->user();
        $referrer = User::where('referral_code', $request->referral_code)->first();

        if ($user->id === $referrer->id) {
            return response()->json([
                'message' => 'You cannot refer yourself'
            ], 422);
        }

        $existingReferral = Referral::where('referred_id', $user->id)->exists();
        if ($existingReferral) {
            return response()->json([
                'message' => 'You have already used a referral code'
            ], 422);
        }

        $referral = $this->referralService->createReferral($referrer, $user);

        return response()->json([
            'message' => 'Referral code applied successfully',
            'referral' => $referral
        ]);
    }

    public function getReferralStats(Request $request): JsonResponse
    {
        $user = $request->user();
        $referrals = Referral::where('referrer_id', $user->id)
            ->with('referred:id,name,email')
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

} 