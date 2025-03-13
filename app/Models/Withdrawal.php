<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'status',
        'txn_id',
        'admin_note',
        'bank_account_id',
    ];

    public function getuser( )
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function userBankAccount( )
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

}
