<?php

namespace App;

use App\Models\Customer;
use App\Models\Rider;
use App\Models\Vendor;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','login_mode',
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

    public function vendor()
    {
        return $this->hasOne(Vendor::class,'user_id','id');
    }

    public function rider()
    {
        return $this->hasOne(Rider::class,'user_id','id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class,'user_id','id');

    }
}
