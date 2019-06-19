<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Helpers\MpesaException;
use App\Helpers\MpesaHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    //

    public function topUp(Request $request)
    {

        Log::warning($request->all());

        $valid = Validator::make($request->all(),[
            'phone_number'=>'required',
            'amount'=>'required',
            'account'=>'required'
        ]);

        if(!$valid->fails()){


            try{
                $m = new MpesaHelper();
                $m->performStkPush(request('phone_number'),request('amount'),request('account'));
            }catch (MpesaException $e){
                return response()->json([
                    'success'=>false,
                    'message'=>$e->getMessage()
                ]);
            }

        }else{
            return response()->json([
                'success'=>false,
                'message'=>"Input data error"
            ]);
        }

        return response()->json([
            'success'=>true
        ]);

    }
}
