<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, Loggable;

    // protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'mobile',
        'status',
        'dial_code',
        'image',
        'otp_request_id',
        'referral_code',
        'referral_balance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    public function getRoleCodes()
    {
        $user = Auth::user();
        return $roles = Role::where('name',$user->getRoleNames())->get();
    }

    public function referrer()
    {
        return $this->hasMany(Referral::class,'referred_id');
    }
    public function clicks()
    {
        return $this->hasMany(Campaign::class,'user_id');
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }
    public function approvedConversions()
    {
        return $this->hasMany(Conversion::class,'user_id')->where('conversion_status','approved');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class,'user_id');
    }

    public function withdrawls($status)
    {
        return $this->hasMany(Withdrawal::class,'user_id')->where('status',$status);
    }

    
}
