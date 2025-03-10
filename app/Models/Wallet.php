<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
        'withdrawn_balance',
        'currency',
        'wallet_type',
        'is_active',
    ];

    public function user()
    {
         return   $this->hasOne(User::class,'id','user_id');
    }}
