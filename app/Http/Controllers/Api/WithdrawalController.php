<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $withdrawals = Withdrawal::with('userBankAccount')->where('user_id', $request->user()->id)->paginate(10);
        return response()->json($withdrawals);
    }


    public function store(Request $request)
    {
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
