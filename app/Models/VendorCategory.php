<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    //
    protected $guarded = [];

    public function vendors()
    {
        return $this->hasMany(Vendor::class,'category_id','id');
    }

    public function topVendors(){
        return $this->vendors()->take(5);
    }
}
