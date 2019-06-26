<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    public $incrementing = false;
    protected $guarded = [];

    public function account()
    {
        return $this->hasOne(Account::class,'id','account_id');
    }

}
