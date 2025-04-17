<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;


class Setting extends Model
{
    use HasFactory, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'website_title',
        'website_logo_dark',
        'website_logo_light',
        'website_logo_small',
        'website_favicon',
        'meta_title',
        'meta_description',
        'meta_tag',
        'currency_id',
        'address',
        'phone',
        'email',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'github',
        //staic OTP
        'static_otp',
        // SMTP fields
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        // OTP fields
        'otp_service',
        'otp_api_key',
        'otp_api_secret',
        'otp_sender_id',
        'otp_template_id',
        'otp_enabled',
        'refferal_amount',
        'firebase_json_file'        
    ];

    public function currency()
    {
        return $this->belongsTo(currency::class, 'currency_id');
    }

}
