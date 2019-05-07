<?php

namespace App\Models;

use App\Traits\CreateOrUpdateExcept;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    //
    use CreateOrUpdateExcept;

    public $incrementing = false;

    protected $guarded = [];
}
