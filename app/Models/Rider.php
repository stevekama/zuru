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


    public function scopeCloseTo($query, $location, $radius = 25)
    {
        $haversine = "(6371 * acos(cos(radians($location->latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($location->longitude)) + sin(radians($location->latitude)) * sin(radians(latitude))))";
        return $query
            ->select(['id'])
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius]);
    }

    public function rides()
    {
        return $this->hasMany(RiderOrder::class,'rider_id','id');
    }
}
