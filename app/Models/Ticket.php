<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'order_datetime',
        'order_amount',
        'order_status',
        'status',
        'subject',
        'description',
        'image_url'
    ];

    protected $casts = [
        'order_datetime' => 'datetime',
        'order_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
} 