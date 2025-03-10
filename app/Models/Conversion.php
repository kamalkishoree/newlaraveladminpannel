<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{    


    protected $fillable =[
        'user_id',
        'click_id',
        'unique_source_id',
        'method',
        'sale',
        'p1',
        'p2',
        'p3',
        'p4',
        'p5',
        'sub1',
        'txn_id',
        'note',
        'currency',
        'payout',
        'brand',
        'status',
        'campaign_id',
        'campaign_name',
];
    

use HasFactory;

    public function campaigns()
    {
        return   $this->hasOne(Campaign::class,'unique_source_id','unique_source_id');
    }

}
