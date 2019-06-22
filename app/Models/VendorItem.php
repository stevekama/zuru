<?php

namespace App\Models;

use App\Traits\CreateOrUpdateExcept;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorItem extends Model
{
    //
    use CreateOrUpdateExcept,SoftDeletes;

    public $incrementing = false;
    protected $guarded = [];
    protected $appends = array('shop');


    public function vendor()
    {
        return $this->hasOne(Vendor::class,'id','vendor_id');
    }

    public function getShopAttribute()
    {
        return $this->vendor()->select('shop_name')->pluck('shop_name')[0];
    }

    public function purchases()
    {
        return $this->hasMany(OrderItems::class,'product_id','id');
    }
}
