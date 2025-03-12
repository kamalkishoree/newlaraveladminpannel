<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccountRequest;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserBankAccountController extends Controller
{
    /**
     * Display a listing of the bank accounts.
     */
    public function index(): JsonResponse
    {

        $bankAccounts = auth()->user()->bankAccounts()->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $bankAccounts
        ]);
    }

    /**
     * Store a newly created bank account.
     */
    public function store(BankAccountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // If this is the first account or is_default is true, handle default status
        if ($request->input('is_default', false) || !auth()->user()->bankAccounts()->exists()) {
            auth()->user()->bankAccounts()->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $bankAccount = BankAccount::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account created successfully',
            'data' => $bankAccount
        ], 201);
    }

    /**
     * Display the specified bank account.
     */
    public function show(BankAccount $bankAccount): JsonResponse
    {
        if ($bankAccount->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $bankAccount
        ]);
    }

    /**
     * Update the specified bank account.
     */
    public function update(BankAccountRequest $request, BankAccount $bankAccount): JsonResponse
    {
        if ($bankAccount->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $data = $request->validated();

        // Handle default status
        if ($request->input('is_default', false)) {
            auth()->user()->bankAccounts()->where('id', '!=', $bankAccount->id)
                ->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $bankAccount->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account updated successfully',
            'data' => $bankAccount
        ]);
    }

    /**
     * Remove the specified bank account.
     */
    public function destroy(BankAccount $bankAccount): JsonResponse
    {
        if ($bankAccount->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // If deleting default account, make another one default
        if ($bankAccount->is_default) {
            $newDefault = auth()->user()->bankAccounts()
                ->where('id', '!=', $bankAccount->id)
                ->first();
            
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $bankAccount->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account deleted successfully'
        ]);
    }

    /**
     * Set a bank account as default.
     */
    public function setDefault(BankAccount $bankAccount): JsonResponse
    {
        if ($bankAccount->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        auth()->user()->bankAccounts()->update(['is_default' => false]);
        $bankAccount->update(['is_default' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account set as default successfully',
            'data' => $bankAccount
        ]);
    }
}
