<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Vendor;
use App\Models\VendorCategory;
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
            'longitude'=>'required|numeric',
            'category_id'=>'required|exists:vendor_categories,id'
        ]);

        /*
         * Create the vendor
         */
        $vendor = $request->all();
        $vendor['id'] = Uuid::generate();
        $vendor['user_id'] = Auth::id();
        Vendor::createOrUpdateExcept(['user_id'=>Auth::id()],$vendor,['id']);
        return response()->json(['success'=>true]);

    }

    public function getVendors(Vendor $vendor)
    {
        return response()->json($vendor);
    }


    public function getVendorCategories()
    {
        return response()->json(VendorCategory::all());
    }

    public function getShops()
    {

        $data = VendorCategory::with(['vendors'=>function($query){
            /*
             * check latitude and longitude radius
             */
            return $query->limit(5);
        }])->get();
        return response()->json($data);
        
    }
}
