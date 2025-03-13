<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
   public function wallet(Request $request)
   {
     $user = Auth::user();
     $wallet = Wallet::where('user_id',$user->id)->first();
     $transaction = Transaction::where('user_id',$user->id)->get();

     if(is_null($wallet))
     {
        $wallet = Wallet::create([
            'user_id'=>$user->id,
            'balance'=>0,
            'pending_balance'=>0,
            'withdrawn_balance'=>0,
            'currency' => 'INR',
            'wallet_type'=>'default',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wallet transaction data.',
            'wallet'  => $wallet,
            'transaction' => $transaction
        ], 200);
     }

        return response()->json([
            'success' => true,
            'message' => 'Wallet & transactiondata.',
            'wallet'  => $wallet,
            'transaction' => $transaction
        ], 200);

    }

} 