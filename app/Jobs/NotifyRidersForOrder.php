<?php

namespace App\Jobs;

use App\Helpers\OneSignalHelper;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Vendor;
use App\Models\VendorItem;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class NotifyRidersForOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $order;
    private $radius = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        //
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        /*
         * create tags
         * "field": "tag", "key": "level", "relation": ">", "value": "10""operator": "OR"
         */


        $data=['order_id'=>$this->order];
        $_order = Order::find($this->order);
        $item = $_order->items()->first();
        $product = VendorItem::where('id',$item->product_id)->first();
        $vendor = Vendor::find($product->vendor_id);

        $haversine = "(6371 * acos(cos(radians($vendor->latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($vendor->longitude)) + sin(radians($vendor->latitude)) * sin(radians(latitude))))";
        $riders = Rider::select('riders.user_id')->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$this->radius])
            ->get()->pluck('user_id');

        $tags =[];
        $users = User::whereIn('id',$riders)->get();
        $helper = new OneSignalHelper();

        foreach ($users as $key=>$user){
            $tags[] = ["field"=>"tag","key"=>"user","relation"=>"exists"];
            $tags[] = ["operator"=>"AND"];
            $tags[] = ["field"=>"tag","key"=> "user","relation"=> "=","value"=>$user->id];
            if($key!=count($users)-1)
                $tags[] = ["operator"=>"OR"];

        }

        Log::info($helper->sendMessage($data,$tags,"Zuru orders","An order is ready for pick up"));
    }
}
