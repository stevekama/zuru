<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Customer;
use App\Models\Rider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class CustomerController extends Controller
{
    //
    public function getSelfCustomer()
    {
        $user = Auth::user();
        $user->login_mode = 0;
        $user->save();

        /*
         * create customer record
         */

        $customer = ["user_id"=>Auth::id(),"id"=>Uuid::generate()];
        Customer::createOrUpdateExcept(['user_id'=>Auth::id()],$customer,['id']);

        return response()->json(Auth::user()->customer);
    }

}
