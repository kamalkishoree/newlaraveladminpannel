<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_type', // 'credit', 'debit'
        'amount',
        'balance_after',
        'currency',
        'status', // 'pending', 'completed', 'failed'
        'source', // 'conversion', 'withdrawal'
        'txn_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function createTransaction($user_id,$amount,$transaction_type,$source,$status,$txn_id)
    {
        $transaction = Self::create([
            'user_id' => $user_id,
            'amount' => $amount,
            'transaction_type' => $transaction_type,
            'source' => $source,
            'status' => $status,
            'txn_id' => $txn_id
        ]);
        return $transaction;
    }
}
