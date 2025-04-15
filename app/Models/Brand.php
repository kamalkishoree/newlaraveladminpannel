<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'category_id',
        'target_url',
        'parent_id',
        'sort_order',
        'is_active',
        'is_new',
        'is_top',
        'is_feature',
        'profit_tracking_hours',
        'profit_confirmation_days',
        'cashback_profit',
        'cashback_terms',
        'payout_type',
        'payout_amount',
        'clocking_url',
        'affiliate_network_id',
        'status'
    ];

    public function cashbackRates()
    {
        return $this->hasMany(CashbackRate::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
