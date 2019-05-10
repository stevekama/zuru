<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Vendor;
use App\Models\VendorCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Webpatser\Uuid\Uuid;

class ShopsController extends Controller
{
    //

    public function getVendorImage($filename)
    {
        $path = public_path('files/vendors/' . $filename);
        return \response()->download($path);
    }

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

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            #requery vital data for the upload
            $image = Input::file('avatar');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('files/vendors/');
            #create path if it does not exist and move the trimmed file
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image->getRealPath())->fit(500, 500)->save($path . $filename);
            $vendor['avatar'] = $filename;

        }
        $vendor['id'] = Uuid::generate();
        $vendor['user_id'] = Auth::id();
        Vendor::createOrUpdateExcept(['user_id'=>Auth::id()],$vendor,['id']);
        return response()->json(['success'=>true]);

    }

    public function getVendors(Vendor $vendor)
    {
        return response()->json($vendor);
    }

    public function getSelfVendors()
    {
        return response()->json(Auth::user()->vendor);

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

    public function getCategoryShops(VendorCategory $category)
    {
        return response()->json($category->vendors);
    }
}
