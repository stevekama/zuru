<?php

namespace App\Models;

use App\Traits\CreateOrUpdateExcept;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    use CreateOrUpdateExcept;

    public $incrementing = false;



    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(VendorItem::class,'vendor_id','id');
    }
}
