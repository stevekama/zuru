<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class OrdersController extends Controller
{
    //

    public function index($status)
    {
        return View::make('backend.orders.index')->withOrders(Order::all());
    }

    public function view(Order $order)
    {

        return View::make('backend.orders.view')->withOrder($order);
    }
}
