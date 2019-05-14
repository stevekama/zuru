<?php

namespace App\Http\Controllers\Api\Commerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    //
    public function getSelfCustomer()
    {
        $user = Auth::user();
        $user->login_mode = 0;
        $user->save();
        return response()->json($user->customer);
    }

}
