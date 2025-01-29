<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_name',
        'sms_from',
        'api_key',
        'api_secret',
        'app_id',
        'status',
    ];
}
