<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;


    protected $fillable = [
        'brand_id',
        'headline',
        'sub_headline',
        'target_url',
        'clocking_url',
        'expires_at',
        'description',
        'sharing_message',
        'status',
        'code',
        'is_active',
        'is_new',
        'is_top',
        'is_feature',
        'status'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
