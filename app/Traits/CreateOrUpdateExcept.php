<?php

namespace App\Traits;

trait CreateOrUpdateExcept{

    protected static function createOrUpdateExcept($condition,$attributes,$except){

        $_model = static::query()->where($condition)->get();
        if($_model!=null){
            $_attributes = array_diff_key($attributes,array_flip($except));
            return static::query()->where($condition)->update($_attributes);
        }else{
            return static::query()->create($attributes);
        }

    }

}