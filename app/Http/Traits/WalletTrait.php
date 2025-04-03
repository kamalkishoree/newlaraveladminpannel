<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use App\Models\{Wallet,Transaction};
use Illuminate\Support\Str;
trait WalletTrait{

    public function updateWallet($brand,$user_id,$amount,$status)
    {
  
        $amount_to_update = 0;
        if($brand->payout_type == 'flat')
        {
            $amount_to_update = $brand->payout_amount;
        }
        elseif($brand->payout_type == 'percentage')
        {
            $amount_to_update = $amount * $brand->payout_amount / 100;
        }
        elseif($brand->payout_type == 'custom')
        {
            $amount_to_update = $brand->payout_amount;
        }
         $wallet = Wallet::where('user_id',$user_id)->first();
         $wallet = Wallet::firstOrCreate(
            ['user_id' => $user_id], // Check condition
            ['pending_balance' => 0] // Default values if creating new
         );
        if ($status == 'pending') {
            $wallet->increment('pending_balance', $amount_to_update);
            Transaction::createTransaction($user_id, $amount_to_update, 'credit', 'conversion', 'pending', '');
        }
        
        if ($status == 'success') {
            // Ensure sufficient pending balance before subtracting
            if ($wallet->pending_balance >= $amount_to_update) {
                $wallet->decrement('pending_balance', $amount_to_update);
                $wallet->increment('withdrawn_balance', $amount_to_update);
                Transaction::createTransaction($user_id, $amount_to_update, 'credit', 'conversion', 'success', '');
            } else {
                \Log::error("Insufficient pending balance for user: $user_id");
            }
        }
        
        if ($status == 'rejected') {
            // Ensure sufficient pending balance before subtracting
            if ($wallet->pending_balance >= $amount_to_update) {
                $wallet->decrement('pending_balance', $amount_to_update);
                Transaction::createTransaction($user_id, $amount_to_update, 'credit', 'conversion', 'rejected', '');
            } else {
                \Log::error("Insufficient pending balance to reject for user: $user_id");
            }
        }
       
    }

  
}