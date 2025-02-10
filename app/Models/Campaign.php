<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

     protected $fillable = [
          'campaign_id','unique_p1_id','brand_id','campaign_id','campaign_provider_id','user_id','pub_id'
     ];

     public function brand()
     {
          return   $this->hasOne(Brand::class,'id','brand_id');
     }
   
}
