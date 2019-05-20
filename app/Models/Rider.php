<?php

namespace App\Models;

use App\Traits\CreateOrUpdateExcept;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    //
    use CreateOrUpdateExcept;

    public $incrementing = false;

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
