<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Vendor;
use App\Models\VendorItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Webpatser\Uuid\Uuid;

class ProductsController extends Controller
{
    //

    public function getProductImage($filename)
    {
        $path = public_path('files/items/' . $filename);
        return \response()->download($path);
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'description'=>'required',
            'price'=>'numeric|required',
            'id'=>'sometimes|exists:vendor_items'
        ]);

        $data = $request->all();
        $vendor = Auth::user()->vendor;
        if($vendor==null){
            return response()->json([
                'success'=>false,
                'message'=>"User does not have vendor privileges"
            ],422);
        }
        $data['vendor_id']=$vendor->id;


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

    public function availability(Request $request)
    {
       $this->validate($request, [
           'product_id'=>'required',
           'is_available'=>'required'
       ]);

       $vendor_item = VendorItem::find(request('product_id'));
       $vendor_item->is_available = request('is_available');
       $vendor_item->save();

       return response()->json(
           ['success'=>true]
       );
    }

    public function deleteProduct(Request $request)
    {
        $this->validate($request, [
            'product_id'=>'required',
        ]);

        $vendor_item = VendorItem::find(request('product_id'));
        $vendor_item->delete();
        return response()->json(
            ['success'=>true]
        );
    }

    public function getZuru()
    {
        $p = VendorItem::withCount('purchases')->orderBy('purchases_count', 'desc')
            ->get();
        return response()->json($p);
    }

    public function getHighestRated()
    {
        $p = VendorItem::orderBy('rating', 'desc')
            ->limit(5)
            ->get();
        return response()->json($p);
    }

    public function getHighestPurchase()
    {
        $p = VendorItem::withCount('purchases')->orderBy('purchases_count', 'desc')
            ->limit(5)
            ->get();
        return response()->json($p);
    }
}
