<?php

namespace App\Models;

use App\Traits\CreateOrUpdateExcept;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    use CreateOrUpdateExcept;

    protected $guarded = [];
}
