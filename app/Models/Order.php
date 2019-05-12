<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //

    public function items()
    {
        return $this->hasMany(OrderItems::class,'order_id','id');
    }
}
