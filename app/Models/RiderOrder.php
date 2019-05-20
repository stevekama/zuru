<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderOrder extends Model
{
    //
    protected $guarded = [];

    public function rider()
    {
        return $this->hasOne(Rider::class,'id','rider_id');
    }
}
