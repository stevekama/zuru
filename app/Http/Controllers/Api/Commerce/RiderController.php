<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Rider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class RiderController extends Controller
{
    //
    public function getSelfRider()
    {
        $user = Auth::user();
        $user->login_mode = 2;
        $user->save();
        return response()->json($user->rider);

    }


    public function storeRider(Request $request)
    {
        $this->validate($request,
            ['latitude'=>'required',
                'longitude'=>'required']);
        $rider = $request->all();
        $rider['id'] = Uuid::generate();
        $rider['user_id'] = Auth::id();
        Rider::createOrUpdateExcept(['user_id'=>Auth::id()],$rider,['id']);

        return response()->json([
            'success'=>true
        ]);
    }
}
