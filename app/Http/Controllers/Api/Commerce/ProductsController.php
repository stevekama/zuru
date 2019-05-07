<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Vendor;
use App\Models\VendorItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
