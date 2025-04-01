<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Support\Str;

class ReferralService
{
    public function generateReferralCode(): string
    {
        return strtoupper(Str::random(8));
    }

    public function createReferral(User $referrer, User $referred): Referral
    {
        return Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => $referred->id,
            'referral_code' => $referrer->referral_code,
            'status' => 'pending',
            'reward_amount' => config('referral.reward_amount', 10.00)
        ]);
    }

    public function completeReferral(Referral $referral): void
    {
        $referral->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        $referrer = $referral->referrer;
        $referrer->increment('referral_balance', $referral->reward_amount);
    }
} 