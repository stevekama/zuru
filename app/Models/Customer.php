<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    public function customer()
    {
        return $this->hasOne(Customer::class,'user_id','id');
    }
}
