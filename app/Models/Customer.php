<?php

namespace App\Models;

use App\Traits\CreateOrUpdateExcept;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    use CreateOrUpdateExcept;


    public function customer()
    {
        return $this->hasOne(Customer::class,'user_id','id');
    }
}
