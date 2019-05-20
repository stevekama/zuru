<?php

namespace App\Http\Controllers\Backend;

use App\Models\Vendor;
use App\Models\VendorItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductsController extends Controller
{
    //

    public function list()
    {
        return view('backend.products.index')->withItems(VendorItem::all());
    }

    public function create()
    {
        return view('backend.products.form')->withVendors(Vendor::all());
    }

    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[

        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors($validator);
        }

        if($request->has('id') && request('id')!=null){
            $product = VendorItem::find(request('id'));
        }else{
            $product = new VendorItem();
        }

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
            $product->avatar = $filename;

        }

        $product->name = request('name');
        $product->price = request('price');
        $product->vendor_id = request('vendor_id');
        $product->description = request('description');
        $product->packaging_cost = request('packaging_cost');
        $product->previous_price = request('previous_price');
        $product->is_on_offer = request('is_on_offer');
        $product->save();

        return redirect()->route('backend.products.list');


    }

    public function edit(VendorItem $item)
    {

        Session::flash('_old_input',$item);
        return view('backend.products.form')->withVendors(Vendor::all());;
    }
}
