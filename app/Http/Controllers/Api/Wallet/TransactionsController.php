<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Helpers\MpesaException;
use App\Helpers\MpesaHelper;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;

class TransactionsController extends Controller
{
    //

    public function withdraw(Request $request)
    {
        $acc = Auth::user()->account;

        $this->validate($request,[
            'amount'=>'required'
        ]);

        if($acc->balance>=request('amount')){

            $trans_data = [
                'account_id'=>$acc->id,
                'id'=>Uuid::generate()->string,
                'amount'=>request('amount'),
                'type'=>2
            ];
            Transaction::create($trans_data);

            return response()->json([
                'success'=>true
            ]);
        }else{
            return response()->json([
                'success'=>false
            ]);
        }

    }

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
