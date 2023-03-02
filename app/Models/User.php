<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable,HasRoles,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone_number','mobile_number','user_type','status','is_subscribed','added_by_id','profile_image','country_code','dob','gender','noti_via_nitification','noti_via_email','vendor_approved','verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function device_detail()
    {
        return $this->hasOne('App\Models\DeviceDetail','user_id');
    }

    public function restaurant()
    {
        return $this->hasOne('App\Models\Restaurant','owner_id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\Address','owner_id')->where('owner_type','Customer');
    }
}
