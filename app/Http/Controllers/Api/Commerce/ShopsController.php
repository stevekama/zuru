<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class ShopsController extends Controller
{
    //

    public function createVendor(Request $request)
    {
        $this->validate($request,[
            'shop_name'=>'required',
            'shop_description'=>'required',
            'location_string'=>'required',
            'latitude'=>'required|numeric',
            'longitude'=>'required|numeric'
        ]);

        /*
         * Create the vendor
         */

        $vendor = $request->all();
        $vendor['id'] = Uuid::generate();
        $vendor['user_id'] = Auth::id();

//        Vendor::createOrUpdate(['user_id'=>Auth::id()],$vendor);

        return response()->json(Vendor::createOrUpdateExcept(['user_id'=>Auth::id()],$vendor,['id']));
    }

    public function getVendors()
    {
        $vendors = Vendor::all();
        return response()->json($vendors);
    }
}
