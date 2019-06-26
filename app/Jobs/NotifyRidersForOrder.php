<?php

namespace App\Jobs;

use App\Helpers\OneSignalHelper;
use App\Models\Order;
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
        $tags =[];
        $users = User::all();
        $helper = new OneSignalHelper();

        foreach ($users as $key=>$user){
            $tags[] = ["field"=>"tag","key"=>"user","relation"=>"exists"];
            $tags[] = ["operator"=>"AND"];
            $tags[] = ["field"=>"tag","key"=> "user","relation"=> "=","value"=>$user->id];
            if($key!=count($users)-1)
                $tags[] = ["operator"=>"OR"];

        }

        $data=['order_id'=>$this->order];




        Log::info($helper->sendMessage($data,$tags,"Zuru orders","An order is ready for pick up"));
    }
}
