<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Jobs\NotifyRidersForOrder;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Rider;
use App\Models\RiderModes;
use App\Models\RiderOrder;
use App\Models\Vendor;
use App\Models\VendorItem;
use Exception;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;

class OrdersController extends Controller
{
    //
    public function store(Request $request)
    {

        $data = json_decode($request->getContent());

        $user = Auth::user();

        if($user->account==null){
            return response()->json([
                'success'=>false,
                'message'=>"You do not have a zuru wallet yet. Create one under your profile"
            ]);
        }

        /*
         * get the total price of the order and consider if the account
         */
        $_price = collect($data->products_in_cart)->sum(function ($value){
            $item = VendorItem::where('id',$value->id)->select('price')->first();
            return $item->price*$value->quantity;
        });
        $_price+=$data->distance_cost;


//        if($user->account->balance < $_price){
//            return response()->json([
//                'success'=>false,
//                'message'=>"Your balance is not enough to cover the order, please top up " . ($_price-$user->account->balance)
//            ]);
//        }



        /*
         * Create an order record
         */
        $order = new Order();
        $order->user_id = Auth::id();
        $order->id=Uuid::generate()->string;
        $order->location = $data->location;
        $order->phone=$data->phone;
        $order->delivery_cost=$data->distance_cost;
        $order->order_no=$this->generateOrderNo();
        $order->save();

        foreach ($data->products_in_cart as $item){
            $order_item=[
                'product_id'=>$item->id,
                'qty'=>$item->quantity,
                'selling_price'=>VendorItem::where('id',$item->id)->select('price')->first()->price,
                'order_id'=>$order->id,
            ];
            OrderItems::create($order_item);
        }


        $this->dispatch(new NotifyRidersForOrder($order->id));

        return response()->json([
            'success'=>true
        ]);
    }

    public function rate(Order $order,Request $request){

        $data = json_decode($request->getContent());

        /*
         * update products for their ratings
         */
        foreach ($data->ratings as $order_rating){
            $product = VendorItem::find($order_rating->item_id);
            $new_rating = ($product->rating+$order_rating->rating)/2;
            $product->rating = $new_rating;
            $product->save();
        }

        $ride = $order->rides()->orderBy('created_at', 'desc')->first();
        if($ride!=null){
            $rider = $ride->rider;
            $r = ($rider->rating+$data->rider_rating)/2;
            $rider->rating = $r;
        }

        return response()->json([
            'success'=>true
        ]);

    }

    public function customer()
    {
        return response()->json(Order::where('user_id',Auth::id())->with(['items'])
            ->withCount([
                'items AS total' => function ($query) {
                    $query->select(DB::raw("SUM(selling_price*qty) as total"));
                }
            ])
            ->with(['items.product'=>function($query){
                $query->withTrashed();
            }])
            ->get());
    }

    public function vendor()
    {
        $vendor = Auth::user()->vendor;

        $orders = Order::whereHas('items.product',function ($query) use($vendor){
            $query->where('vendor_items.vendor_id',$vendor->id);
        })
            ->withCount([
                'items AS total' => function ($query) {
                    $query->select(DB::raw("SUM(selling_price*qty) as total"));
                }
            ])
            ->with(['items'=>function($query)use($vendor){
            $query->whereHas('product',function ($query)use($vendor){
                $query->where('vendor_items.vendor_id',$vendor->id);
            })->with('product');
        }])->get();

        return $orders;
    }

    public function rider()
    {

        $rider = Auth::user()->rider;
        if($rider==null){
            return response()->json([]);
        }

        $orders = Order::join('rider_orders','orders.id','=','rider_orders.order_id')
            ->join('riders','rider_orders.rider_id','riders.id')
            ->select('orders.*')
            ->withCount([
                'items AS total' => function ($query) {
                    $query->select(DB::raw("SUM(selling_price*qty) as total"));
                }
            ])
            ->with(['items'=>function($query){
                $query->with(['product'=>function($query){
                        $query->withTrashed();
                }]);
            }])
            ->where('riders.user_id',Auth::id())
            ->get();

        return response()->json($orders);
    }


    public function pending()
    {
        $orders = Order::select('orders.*')
            ->withCount([
                'items AS total' => function ($query) {
                    $query->select(DB::raw("SUM(selling_price*qty) as total"));
                }
            ])
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

        if(count($order->rides)>0){
            return response()->json([
                'success'=>false,
                'message'=>'The order has already been assigned'
            ]);
        }


        $rider = Auth::user()->rider;
        if($rider==null){
            return response()->json([
                'success'=>false,
                'message'=>'422'
            ]);
        }

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

    public function calculatePrice(Request $request)
    {

        $result = ["distance"=>0,"time"=>0,"cost"=>0];

        Log::warning($request->getContent());
        $data = json_decode($request->getContent());
        $riderMode = RiderModes::find($data->rider_mode);
        $items = [];
        foreach ($data->products_in_cart as $item) {
            $items[] = $item->id;
        }

        $vendors = Vendor::join('vendor_items','vendors.id','vendor_items.vendor_id')
            ->select(['vendors.id','vendors.latitude','vendors.longitude'])
            ->whereIn('vendor_items.id',$items)
            ->groupBy('vendors.id')
            ->get();
        $_start_point=["lat"=>$data->latitude,"lng"=>$data->longititude];
        foreach ($vendors as $key=>$vendor){
                $_current_point=["lat"=>$vendor->latitude,"lng"=>$vendor->longitude];
                if($vendor->latitude!=0 && $vendor->longitude!=0){
                    try{
                        $stat=$this->GetDrivingDistance($_start_point["lat"],
                            $_current_point["lat"],
                            $_start_point["lng"],
                            $_current_point["lng"]);
                    }catch (Exception $exception){
                        $stat=$this->getDistanceBetweenPoints($_start_point["lat"],
                            $_current_point["lat"],
                            $_start_point["lng"],
                            $_current_point["lng"]);
                    }
                }else{
                    $stat = array('distance' => 1, 'time' => 0);
                }

                $result["distance"]+=$stat["distance"];
                $result["time"]+=$stat["time"];
                $_start_point=["lat"=>$vendor->latitude,"lng"=>$vendor->longitude];

        }
        $result["distance"]/=1000;
        $result["time"]/=60;
        $result["cost"] =$result["distance"]/1000 * $riderMode->price_per_km;

        return response()->json($result);
    }

    function GetDrivingDistance($lat1, $lat2, $long1, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyD8YU48m7RJufra0o6ePuY2FwMX5AMZ2EU&origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['value'];

        return array('distance' => $dist, 'time' => $time);
    }

    function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        return $kilometers;
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
