<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{

    use HasFactory;
    protected $fillable = [
        'title',
        'type',
        'message',
        'image_url',
        'users',
        'email_subject',
        'email_body',
        'description',
        'push_url_option',
        'push_url_option_value',
        'schedule_datetime',
        'status',
    ];

}
