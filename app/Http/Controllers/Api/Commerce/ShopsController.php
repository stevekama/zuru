<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\ResetCode;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Models\VendorItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
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

    public function getRiderModeAvatar($filename)
    {
        $path = public_path('files/rider_modes/' . $filename);
        return response()->download($path);
    }

    public function getUserImage($filename)
    {
        $path = public_path('files/users/' . $filename);
        return response()->download($path);
    }

    public function getCategoryAvatar($filename)
    {
        $path = public_path('files/categories/' . $filename);
        return response()->download($path);
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

        if($request->hasFile('idfile') && $request->file('idfile')->isValid()) {
            #requery vital data for the upload
            $image = Input::file('idfile');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('files/vendors/');
            #create path if it does not exist and move the trimmed file
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image->getRealPath())->fit(500, 500)->save($path . $filename);
            $vendor['idfile'] = $filename;
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
        $user = Auth::user();
        $user->login_mode = 1;
        $user->save();
        return response()->json(Auth::user()->vendor);

    }

    public function getVendorCategories()
    {
        return response()->json(VendorCategory::all());
    }

    public function getShops(Request $request)
    {
        $radius = 30;
        if($request->has('latitude') && $request->input('latitude')!=0){
            $haversine = "(6371 * acos(cos(radians($request->latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($request->longitude)) + sin(radians($request->latitude)) * sin(radians(latitude))))";

            $data = VendorCategory::get();
            $data->each(function($user) use ($haversine,$radius){
                $user->load(['topVendors'=>function($query)use ($haversine,$radius){
                    $query->select('vendors.*')->selectRaw("{$haversine} AS distance")
                        ->whereRaw("{$haversine} < ?", [$radius])
                    ->whereRaw("{$haversine} > ?", 0);

                }]);
            });
        }else{
            $data =  [];
        }


        return response()->json($data);
        
    }

    public function getCategoryShops(Request $request,VendorCategory $category)
    {
        $radius = 25;


        if($request->has('latitude') && $request->input('latitude')!=0){
            $haversine = "(6371 * acos(cos(radians($request->latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($request->longitude)) + sin(radians($request->latitude)) * sin(radians(latitude))))";


            $data = Vendor::where('category_id',$category->id)
                ->select('vendors.*')->selectRaw("{$haversine} AS distance")
                ->whereRaw("{$haversine} < ?", [$radius])
                ->whereRaw("{$haversine} > ?", 0)
            ->get();


        }else{
            $data = [];
        }
        return response()->json($data);
    }


    public function getSearch(Request $request)
    {

        $products = VendorItem::where('name', 'like', '%' . Input::get('query') . '%')
            ->get();

        return response()->json($products);
    }
}
