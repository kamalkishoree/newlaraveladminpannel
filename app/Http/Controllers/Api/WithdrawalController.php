<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Http\Traits\WalletTrait;

class WithdrawalController extends Controller
{
    use WalletTrait;
    public function index(Request $request)
    {
        $withdrawals = Withdrawal::with('userBankAccount')->where('user_id', $request->user()->id)->paginate(10);
        return response()->json($withdrawals);
    }


    public function store(Request $request)
    {
        \Log::warning('request',$request->all());
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'bank_account_id' => 'required|exists:bank_accounts,id',
        ]);                     
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $withdrawal = Withdrawal::create([
            'user_id' => $request->user()->id,
            'amount' => $request->amount,
            'currency' => 'INR',
            'status' => 'pending',
            'txn_id' => 'TXN-' . Carbon::now()->format('YmdHis') . rand(1000, 9999),
            'bank_account_id' => $request->bank_account_id,
        ]);
        if($withdrawal)
        {
            $this->updateWalletMain($request->user()->id,$request->amount,'pending');
            Transaction::createTransaction($request->user()->id,$request->amount,'request','wallet','pending','');
        }
        return response()->json(['message' => 'Withdrawal request submitted successfully'], 200);
    }
    public function update(Request $request, $id)
    {
        $withdrawal = Withdrawal::find($id);
        $withdrawal->update($request->all());
        return response()->json(['message' => 'Withdrawal request updated successfully'], 200);
    }               

    public function destroy($id)
    {
        $withdrawal = Withdrawal::find($id);
        $withdrawal->delete();
        return response()->json(['message' => 'Withdrawal request deleted successfully'], 200);
    }
}
