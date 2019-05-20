<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    //

    public function test()
    {
        $riders = Rider::closeTo((object)["latitude"=>-3.880423,"longitude"=>39.816311])->get();
        return response()->json($riders);
    }
}
