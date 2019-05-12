<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Jobs\NotifyRidersForOrder;
use App\Models\Order;
use App\Models\OrderItems;
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

        $this->dispatch(new NotifyRidersForOrder($order));

        return response()->json([
            'success'=>true
        ]);
    }


    public function customer()
    {
        return response()->json(Order::where('user_id',Auth::id())->with(['items','items.product'])->get());
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
