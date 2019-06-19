<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\VendorItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    //

    public function getGeneralStats()
    {
        $vendor = Auth::user()->vendor;

        return response()->json([
            'orders'=>Order::whereHas('items.product',function ($query) use($vendor){$query->where('vendor_items.vendor_id',$vendor->id);})->count(),
            'items_bought'=>VendorItem::whereHas('purchases')->count(),
            'in_stock'=>VendorItem::where('qty','>',0)->count(),
            'out_of_stock'=>VendorItem::where('qty','=',0)->count()
        ]);

    }

    public function sellingStats()
    {

        $best = VendorItem::withCount('purchases')->orderBy('purchases_count','desc')->limit(5)->get();
        $least = VendorItem::withCount('purchases')->orderBy('purchases_count','asc')->limit(5)->get();

        return response()->json([
            'best'=>$best,
            'least'=>$least
        ]);
    }

    public function earnings(Request $request)
    {

        $vendor = Auth::user()->vendor;

        if(!$request->has('start')){
            $start=Carbon::now();
            $stop=Carbon::now()->subMonth(1);
        }else{
            $start=Carbon::createFromFormat('Y-m-d H',$request->input('stop'));
            $stop=Carbon::createFromFormat('Y-m-d H',$request->input('start'));
        }
        $data=[];
        for($stop;$stop<$start;$stop->addDay()){
            $x=clone $stop;
            $xx=clone $stop;




            $amt = OrderItems::whereHas('product',function ($query) use ($vendor){
                        $query->where('vendor_items.vendor_id',$vendor->id);
                    })
                ->select([DB::raw('sum(order_items.selling_price * order_items.qty)+0 AS total')])
                ->whereBetween('order_items.created_at',[$xx->toDateTimeString(),$x->addDay()->toDateTimeString()])
                ->get();

            $data[]=array('date'=>$xx->toDateTimeString(),'amount'=>$amt[0]->total);
        }


        return response()->json($data);

    }
}
