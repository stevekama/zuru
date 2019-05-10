<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Vendor;
use App\Models\VendorItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Webpatser\Uuid\Uuid;

class ProductsController extends Controller
{
    //

    public function store(Request $request)
    {
        $this->validate($request,[
            'vendor_id'=>'required|exists:vendors,id',
            'name'=>'required',
            'description'=>'required',
            'price'=>'numeric|required',
        ]);

        $data = $request->all();


        if($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            #requery vital data for the upload
            $image = Input::file('avatar');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('files/items/');
            #create path if it does not exist and move the trimmed file
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image->getRealPath())->fit(500, 500)->save($path . $filename);
            $data['avatar'] = $filename;

        }


        $data['id']=Uuid::generate()->string;
        VendorItem::createOrUpdateExcept(['id'=>request('id')],$data,['id']);
        return response()->json(['success'=>true]);

    }

    public function fetch($product)
    {
        return response()->json(VendorItem::find($product));
    }

    public function shopProducts($vendor)
    {
        $_vendor = Vendor::find($vendor);
        return response()->json($_vendor->products);

    }
}
