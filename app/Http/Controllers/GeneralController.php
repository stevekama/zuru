<?php

namespace App\Http\Controllers;

use App\Helpers\MpesaHelper;
use App\Jobs\NotifyRidersForOrder;
use App\Models\Order;
use App\Models\Rider;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    //

    public function test(Request $request)
    {
//        $riders = Rider::closeTo((object)["latitude"=>-3.880423,"longitude"=>39.816311])->get();
//        return response()->json($riders);

//        $order = Order::first();
//        $this->dispatch(new NotifyRidersForOrder($order));

        echo "<form action='" . url('test') . "' method='get'>
            <input type='text' name='amt' placeholder='amount eg 10'>
            <input type='text' name='account' placeholder='account eg test'>
            <input type='text' name='number' placeholder='number in format 2547xxxxxxxx'>
            <input type='submit' value='simulate payment' id=''>
            
            </form>";

        if($request->has('amt')){
            $m = new MpesaHelper();
            print_r($m->performStkPush(request('number'),request('amt'),request('account')));
        }



    }
}
