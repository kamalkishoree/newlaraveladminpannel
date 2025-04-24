<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{    


    protected $fillable =[
        'user_id','user_campaign_id','unique_source_id', 'campaign_id', 'campaign_name', 'publisher_id', 'aff_name', 'aff_id', 
        'click_id', 'click_time', 'conversion_id', 'conversion_datetime', 
        'conversion_status', 'payout', 'currency', 'txn_id', 'sale_amount', 
        'click_ip', 'city', 'region', 'isp', 'goal_name', 'goal_id','order_id'
     ];
    

use HasFactory;

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'unique_source_id', 'unique_source_id');
    }

}
