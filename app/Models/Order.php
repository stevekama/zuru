<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public $incrementing = false;
    protected $appends = array('buyer');


    public function items()
    {
        return $this->hasMany(OrderItems::class,'order_id','id');
    }

    public function buyer()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function getBuyerAttribute()
    {
        return $this->buyer()->select('name')->pluck('name')[0];
    }

    public function rides()
    {
       return $this->hasMany(RiderOrder::class,'order_id','id');
    }

    public function vendors(){
        return Order::
            join('order_items','orders.id','order_items.order_id')
            ->join('vendor_items','order_items.product_id','vendor_items.id')
            ->join('vendors','vendor_items.vendor_id','vendors.id')
            ->groupBy('vendors.id')
            ->select(['vendors.id','vendors.latitude','vendors.longitude','vendors.shop_name'])
            ->where('order_items.order_id',$this->id)->get();
    }
}
