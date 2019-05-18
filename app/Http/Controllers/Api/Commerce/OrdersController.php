<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Jobs\NotifyRidersForOrder;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Rider;
use App\Models\RiderOrder;
use App\Models\Vendor;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;

class OrdersController extends Controller
{
    //
    public function store(Request $request)
    {

        $data = json_decode($request->getContent());

        /*
         * Create an order record
         */
        $order = new Order();
        $order->user_id = Auth::id();
        $order->id=Uuid::generate();
        $order->location = $data->location;
        $order->phone=$data->phone;
        $order->order_no=$this->generateOrderNo();
        $order->save();

        foreach ($data->products_in_cart as $item){
            $order_item=[
                'product_id'=>$item->id,
                'qty'=>$item->quantity,
                'order_id'=>$order->id,
            ];
            OrderItems::create($order_item);
        }

        $order = Order::first();

        $this->dispatch(new NotifyRidersForOrder($order));

        return response()->json([
            'success'=>true
        ]);
    }

    public function rate(Order $order,Request $request){

        Log::warning($request->getContent());

    }

    public function customer()
    {
        return response()->json(Order::where('user_id',Auth::id())->with(['items'])
            ->with(['items.product'=>function($query){
                $query->withTrashed();
            }])
            ->get());
    }

    public function vendor()
    {
        $vendor = Auth::user()->vendor;
        $orders = Order::whereHas('items.product',function ($query) use($vendor){
            $query->where('vendor_id',$vendor->id);
        })->with('items')->with(['items.product'=>function($query){
            $query->withTrashed();
        }])->get();
        return $orders;

    }

    public function rider()
    {
        $orders = Order::join('rider_orders','orders.id','=','rider_orders.order_id')

            ->select('orders.*')
            ->with(['items'=>function($query){
                $query->with(['product'=>function($query){
                        $query->withTrashed();
                }]);
            }])
            ->get();

        return response()->json($orders);
    }

    public function accept(Order $order)
    {
        $rider = Auth::user()->rider;
        if($rider==null){
            $_rider=['user_id'=>Auth::id(),'id'=>Uuid::generate()];
            Rider::createOrUpdateExcept(['user_id'=>Auth::id()],$_rider,['id']);
            $rider = Auth::user()->rider;
        }

        /*
         *create an rider order
         */
        if(RiderOrder::where('order_id',$order->id)->first()==null)
            RiderOrder::create(['order_id'=>$order->id,'rider_id'=>$rider->id]);

        $order->status=1;
        $order->save();

        return response()->json([
            'success'=>true
        ]);

    }

    public function acceptCustomer(Order $order)
    {
        $order->status=2;
        $order->save();
        return response()->json([
            'success'=>true
        ]);
    }


    function generateOrderNo(){
        $pin = $this->generatePIN();
        $validator=Validator::make(['order_no'=>$pin],['order_no'=>'unique:orders,order_no']);
        if($validator->fails()){
            return $this->generateOrderNo();
        }else{
            return $pin;
        }
    }

    function generatePIN($digits = 4){
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }
}
