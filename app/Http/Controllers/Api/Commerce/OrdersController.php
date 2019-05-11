<?php

namespace App\Http\Controllers\Api\Commerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    //
    public function store(Request $request)
    {

        $data = json_decode($request->getContent());

        Log::warning(json_encode($data));

        return response()->json([
            'success'=>true
        ]);
    }
}
