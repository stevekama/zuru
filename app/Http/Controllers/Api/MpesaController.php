<?php

namespace App\Http\Controllers\Api;

use App\Models\MpesaTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    //

    public function process(Request $request)
    {
        if($request->has('key') && request('key')=="Fpe1kFh8zgjoCqAVSEF7l0CKfpHonmgj"){

            $data=json_decode($request->getContent(), true);

            Log::warning($data);
            /*
             * verify the data belongs to an account
             */

            MpesaTransaction::create($data);


            $response=[
                'ResultCode'=>0,
                'ResultDesc'=>'The service was accepted successfully',
                'ThirdPartyTransID'=>$data['ThirdPartyTransID']
            ];
            return response()->json($response);

        }else{

            $response=[
                'ResultCode'=>0,
                'ResultDesc'=>'Unauthorised access',
                'ThirdPartyTransID'=>uniqid()
            ];
            return response()->json($response);
        }
    }
}
