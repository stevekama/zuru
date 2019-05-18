<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //
    public $incrementing = false;

    public function transactions()
    {
        return $this->hasMany(Transaction::class,'account_id','id');
    }

}
