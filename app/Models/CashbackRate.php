<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbackRate extends Model
{
    use HasFactory;
    protected $fillable = ['brand_id', 'profit', 'description'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
